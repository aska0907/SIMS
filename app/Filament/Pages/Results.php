<?php

namespace App\Filament\Pages;

use App\Models\Grade;
use App\Models\Subject;
use App\Models\School;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Notifications\Notification;

class Results extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $title = 'Results';
    protected static string $view = 'filament.pages.results';

    public ?string $semester = null;
    public ?string $class = null; // Selected class filter
    public array $components = [];
    public Collection $subjects;

    public function mount(): void
    {
        $this->semester = null;
        $this->class = null;
        $this->components = [];
        $this->subjects = Subject::all();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Filters')
                    ->schema([
                        Forms\Components\Select::make('semester')
                            ->label('Semester')
                            ->options([
                                'Semester 1' => 'Semester 1',
                                'Semester 2' => 'Semester 2',
                            ])
                            ->required()
                            ->live(),

                        Forms\Components\Select::make('class')
                            ->label('Class')
                            ->options([
                                'All' => 'All Classes',
                                'Form 1' => 'Form 1',
                                'Form 2' => 'Form 2',
                                'Form 3' => 'Form 3',
                                'Form 4' => 'Form 4',
                            ])
                            ->required()
                            ->live(),

                        Forms\Components\CheckboxList::make('components')
                            ->label('Include Marks From')
                            ->options([
                                'test1' => 'Test 1',
                                'test2' => 'Test 2',
                                'mid_term' => 'Mid Term',
                                'terminal' => 'Terminal',
                            ])
                            ->columns(2)
                            ->required()
                            ->live(),
                    ])
                    ->collapsible(),
            ]);
    }

    protected function getGrade(float $average): string
    {
        return match(true) {
            $average >= 75 => 'A',
            $average >= 65 => 'B',
            $average >= 50 => 'C',
            $average >= 30 => 'D',
            default => 'F',
        };
    }

    public function getResults(): Collection
    {
        if (empty($this->semester) || empty($this->components)) {
            return collect();
        }

        $query = Grade::with(['student', 'subject'])
            ->where('semester', $this->semester);

        if ($this->class && $this->class !== 'All') {
            $query->where('class', $this->class);
        }

        $grades = $query->get();
        $results = collect();

        foreach ($grades->groupBy('class') as $className => $classGrades) {

            $perStudent = $classGrades->groupBy('student_id')->map(function ($studentGrades) {

                $student = $studentGrades->first()->student;
                $subjectMarks = [];
                $subjectGrades = [];

                $studentSubjects = $student->subjects->pluck('id', 'subject_name'); // [subject_name => id]

                foreach ($studentSubjects as $subjectName => $subjectId) {
                    $grade = $studentGrades->firstWhere('subject_id', $subjectId);

                    $marksForSubject = [];
                    $outOf = 0;
                    if (!$grade) {
                        foreach ($this->components as $comp) {
                            $marksForSubject[$comp] = null;
                            // You may want to set max marks for each component if you have them
                            // $outOf += 0;
                        }
                        $avg = null;
                        $subGrade = 'F';
                        $total = null;
                    } else {
                        $total = 0;
                        $count = 0;
                        foreach ($this->components as $comp) {
                            $value = $grade->$comp;
                            $marksForSubject[$comp] = $value;
                            if (!is_null($value)) {
                                $total += $value;
                                $count++;
                            }
                            // If you have max marks per component, add here, e.g.:
                            // $outOf += $grade->getMaxForComponent($comp);
                            $outOf += 100; // <-- Set your max per component here (e.g., 100)
                        }
                        $avg = $count > 0 ? round($total / $count, 2) : 0;
                        $subGrade = $this->getGrade($avg);
                    }
                    $marksForSubject['total'] = $total;
                    $marksForSubject['out_of'] = $outOf;
                    $marksForSubject['average'] = $avg;

                    $subjectMarks[$subjectName] = $marksForSubject;
                    $subjectGrades[$subjectName] = $subGrade;
                }

                $subjectPoints = array_map(fn($g) => match($g) {
                    'A' => 1,
                    'B' => 2,
                    'C' => 3,
                    'D' => 4,
                    'F' => 5,
                    default => 5,
                }, $subjectGrades);

                $best7Points = collect($subjectPoints)->sort()->take(7);
                $totalPoints = $best7Points->sum();

                // Calculate overall average for the student
                $allAverages = collect($subjectMarks)->pluck('average')->filter(fn($v) => !is_null($v));
                $overallAverage = $allAverages->count() > 0 ? round($allAverages->avg(), 2) : null;

                $division = match(true) {
                    $totalPoints >= 7 && $totalPoints <= 17 => 'Division I',
                    $totalPoints >= 18 && $totalPoints <= 21 => 'Division II',
                    $totalPoints >= 22 && $totalPoints <= 25 => 'Division III',
                    $totalPoints >= 26 && $totalPoints <= 33 => 'Division IV',
                    default => 'Division 0 / Fail',
                };

                return [
                    'student_name' => $student->full_name ?? $student->name ?? 'Unknown',
                    'class' => $studentGrades->first()->class,
                    'marks' => $subjectMarks,
                    'grades' => $subjectGrades,
                    'totalPoints' => $totalPoints,
                    'points' => $totalPoints, // <-- add this
                    'division' => $division,
                    'average' => $overallAverage, // <-- add this
                    'rank' => 0,
                    'profile_picture' => $student->profile_picture, // ✅ include student photo
                ];
            });

            // This sorts by totalPoints (old):
            // $sorted = $perStudent->sortBy('totalPoints')->values()->all();

            // Change to sort by average (descending, so highest average is rank 1):
            $sorted = $perStudent->sortByDesc('average')->values()->all();

            $ranked = [];
            $prevAverage = null;
            $currentRank = 0;
            $position = 0;
            $totalStudents = count($sorted);

            foreach ($sorted as $row) {
                $position++;
                if ($prevAverage === null || $row['average'] < $prevAverage) {
                    $currentRank = $position;
                    $prevAverage = $row['average'];
                }
                $row['rank'] = $currentRank;
                $row['out_of'] = $totalStudents;
                $ranked[] = $row;
            }

            $results = $results->merge($ranked);
        }

        return $results;
    }

    public function exportReportBook()
    {
        $results = $this->getResults();

        if ($results->isEmpty()) {
            Notification::make()
                ->title('No results to export.')
                ->warning()
                ->send();
            return;
        }

        $school = School::find(1); // ✅ school with ID 1

        // Ensure only selected components are passed and used in the view
        $selectedComponents = array_filter($this->components);

        $pdf = Pdf::loadView('filament.pages.results-report-pdf', [
            'results' => $results,
            'semester' => $this->semester,
            'class' => $this->class,
            'components' => $selectedComponents, // Pass only selected components
            'school' => $school,
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'olevel-report-book-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportPdf()
    {
        $results = $this->getResults();

        if ($results->isEmpty()) {
            Notification::make()
                ->title('No results to export.')
                ->warning()
                ->send();
            return;
        }

        $selectedComponents = array_filter($this->components);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('filament.pages.results-pdf', [
            'results' => $results,
            'semester' => $this->semester,
            'class' => $this->class,
            'components' => $selectedComponents,
            'subjects' => $this->subjects->pluck('subject_name'),
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'results-summary-' . now()->format('Y-m-d') . '.pdf');
    }
}
