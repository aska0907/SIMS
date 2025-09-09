<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdvancedSubjectAssignment;
use App\Models\AdvancedStudentCombination;
use App\Models\AdvStudent;
use App\Models\AdvGrade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdvancedGradeController extends Controller
{
    public function index()
    {
        $teacherId = auth()->id();

        // Get all subjects assigned to this teacher with related data
        $assignments = AdvancedSubjectAssignment::with(['subject', 'combination'])
            ->where('user_id', $teacherId)
            ->get();

        // For each assigned subject, fetch students who have the same combination AND class
        foreach ($assignments as $assign) {
            $combinationId = $assign->combination_id;
            $classLevel = $assign->class_level;
            $subjectId = $assign->subject_id;

            // Get students through the pivot table
            $studentCombinations = AdvancedStudentCombination::where('combination_id', $combinationId)
                ->where('class_level', $classLevel)
                ->get();

            $studentIds = $studentCombinations->pluck('adv_student_id')->toArray();

            // Get students with their grades for this subject
            $students = AdvStudent::with(['advGrades' => function($query) use ($subjectId) {
                $query->where('subject_id', $subjectId);
            }])
            ->whereIn('id', $studentIds)
            ->get();

            $assign->students = $students;
        }

        return view('advanced-grades.index', compact('assignments'));
    }





        public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|integer|exists:subjects,id',
            'class' => 'required',
            'grades' => 'required|array',
            'current_semester' => 'required',
            'combination_id' => 'required',
        ]);

        $subjectId = $request->subject_id;
        $class = $request->class;
        $grades = $request->grades;
        $combination_id = $request->combination_id;
       $semesterName = $request->current_semester; // 'sem1' or 'sem2'


        try {
            DB::beginTransaction();

            foreach ($grades as $studentId => $score) {

                // Skip if no score is provided or not an array
                if (!is_array($score) || !array_filter($score, fn($val) => $val !== null && $val !== '')) {
                    continue;
                }

                // Sanitize numeric values
                $score = array_map(function ($val) {
                    return is_numeric($val) ? (int)$val : null;
                }, $score);

                // Fetch existing grade
                $grade = AdvGrade::firstOrNew([
                    'adv_student_id' => $studentId,
                    'subject_id' => $subjectId,
                    'class'      => $class,
                    'semester'   => $semesterName,
                    'combination_id' => $combination_id,
                ]);

                // Update only provided fields
                foreach (['test1','test2','midterm','terminal'] as $field) {
                    if (isset($score[$field]) && $score[$field] !== null) {
                        $dbField = $field === 'midterm' ? 'mid_term' : $field;
                        $grade->$dbField = $score[$field];
                    }
                }

                $grade->save();
            }

            DB::commit();
            return back()->with('success', 'Grades saved successfully!');
        }catch (\Exception $e) {
    DB::rollBack();
    Log::error('Grade save failed: '.$e->getMessage(). ' in '.$e->getFile().':'.$e->getLine());
    return back()->with('error', 'An error occurred while saving grades: '.$e->getMessage());
}
    }
}