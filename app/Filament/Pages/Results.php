<?php

namespace App\Filament\Pages;

use App\Models\Grade;
use App\Models\Subject;
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

                // Only loop through subjects assigned to this student
                $studentSubjects = $student->subjects->pluck('id', 'subject_name'); // [subject_name => id]

                foreach ($studentSubjects as $subjectName => $subjectId) {
                    $grade = $studentGrades->firstWhere('subject_id', $subjectId);

                    if (!$grade) {
                        $avg = null;
                        $subGrade = 'F';
                    } else {
                        $total = 0;
                        $count = 0;
                        foreach ($this->components as $comp) {
                            $value = $grade->$comp;
                            if (!is_null($value)) {
                                $total += $value;
                                $count++;
                            }
                        }
                        $avg = $count > 0 ? round($total / $count, 2) : 0;
                        $subGrade = $this->getGrade($avg);
                    }

                    $subjectMarks[$subjectName] = $avg;
                    $subjectGrades[$subjectName] = $subGrade;
                }

                // Map grades to points
                $subjectPoints = array_map(fn($g) => match($g) {
                    'A' => 1,
                    'B' => 2,
                    'C' => 3,
                    'D' => 4,
                    'F' => 5,
                    default => 5,
                }, $subjectGrades);

                // Best 7 points
                $best7Points = collect($subjectPoints)->sort()->take(7);
                $totalPoints = $best7Points->sum();

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
                    'division' => $division,
                ];
            });

            // Sort students by totalPoints ascending
            $sorted = $perStudent->sortBy('totalPoints')->values()->all();

            // Assign rank per class
            $ranked = [];
            $prevPoints = null;
            $currentRank = 0;
            $position = 0;

            foreach ($sorted as $row) {
                $position++;
                if ($prevPoints === null || $row['totalPoints'] > $prevPoints) {
                    $currentRank = $position;
                    $prevPoints = $row['totalPoints'];
                }
                $row['rank'] = $currentRank;
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

    // Generate PDF per student report book
    $pdf = Pdf::loadView('filament.pages.results-report-pdf', [
        'results'   => $results,
        'semester'  => $this->semester,
        'class'     => $this->class,
        'components'=> $this->components,
    ]);

    return response()->streamDownload(function () use ($pdf) {
        echo $pdf->output();
    }, 'olevel-report-book-' . now()->format('Y-m-d') . '.pdf');
}
  
    public function exportPdf()
    {
        // Use the getResults method to get the data for the report
        $results = $this->getResults();

        // Ensure there are results before attempting to export
        if ($results->isEmpty()) {
            Notification::make()
                ->title('No results to export.')
                ->warning()
                ->send();
            return;
        }

        // Get the subjects for the table headers
        $subjects = array_keys($results->first()['grades']);

        // Generate the PDF from a Blade view
        $pdf = Pdf::loadView('filament.pages.results-pdf', [
            'results' => $results,
            'semester' => $this->semester,
            'class' => $this->class,
            'subjects' => $subjects,
            'components' => $this->components
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'academic-results-' . str_replace(' ', '-', strtolower($this->semester)) . '-' . str_replace(' ', '-', strtolower($this->class)) . '.pdf');
    }
}