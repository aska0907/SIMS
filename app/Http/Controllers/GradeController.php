<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GradeController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|integer|exists:subjects,id',
            'class' => 'required|string|max:50',
            'grades' => 'required|array',
            'current_semester' => 'required|string|in:sem1,sem2',
        ]);

        $subjectId = $request->subject_id;
        $class = $request->class;
        $grades = $request->grades;
        $semesterName = $request->current_semester === 'sem1' ? 'Semester 1' : 'Semester 2';

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
                $grade = Grade::firstOrNew([
                    'student_id' => $studentId,
                    'subject_id' => $subjectId,
                    'class'      => $class,
                    'semester'   => $semesterName,
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
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Grade save failed: '.$e->getMessage());
            return back()->with('error', 'An error occurred while saving grades. Please try again.');
        }
    }
}
