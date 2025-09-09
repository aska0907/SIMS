<?php

namespace App\Filament\Pages;

use App\Models\AdvGrade;
use App\Models\Subject;
use App\Models\AdvStudent;
use App\Models\Combination;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Notifications\Notification;

class AdvResults extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $title = 'Advanced Level Results';
    protected static string $view = 'filament.pages.adv-results';

    public ?string $semester = null;
    public ?string $class = null;
    public ?int $combination_id = null;
    public array $components = [];
    public Collection $subjects;

    public function mount(): void
    {
        $this->semester = null;
        $this->class = null;
        $this->combination_id = null;
        $this->components = [];
        $this->subjects = Subject::all();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Filters')->schema([
                Forms\Components\Select::make('semester')
                    ->label('Semester')
                    ->options([
                        'sem1' => 'Semester 1',
                        'sem2' => 'Semester 2',
                    ])
                    ->required()
                    ->live(),

                Forms\Components\Select::make('class')
                    ->label('Class')
                    ->options([
                        'All' => 'All Classes',
                        'Form 5' => 'Form 5',
                        'Form 6' => 'Form 6',
                    ])
                    ->required()
                    ->live(),

                Forms\Components\Select::make('combination_id')
                    ->label('Combination')
                    ->options(Combination::pluck('name', 'id'))
                    ->searchable()
                    ->live(),
                Forms\Components\Select::make('student_id')
    ->label('Student (optional)')
    ->options(AdvStudent::pluck('full_name', 'id'))
    ->searchable()
    ->placeholder('All Students')
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
            ])->collapsible(),
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

        $query = AdvGrade::with(['student', 'subject'])
            ->where('semester', $this->semester);

        if ($this->class && $this->class !== 'All') {
            $query->where('class', $this->class);
        }

        if ($this->combination_id) {
            $query->where('combination_id', $this->combination_id);
        }

        $grades = $query->get();
        $results = collect();

        // Get core subjects for the selected combination
        $coreSubjects = \DB::table('combination_subject')
            ->where('combination_id', $this->combination_id)
            ->where('type', 'core')
            ->pluck('subject_id')
            ->toArray();

        foreach ($grades->groupBy('adv_student_id') as $studentId => $studentGrades) {
            $student = $studentGrades->first()->student;
            $subjectMarks = [];
            $subjectGrades = [];

            foreach ($studentGrades as $grade) {
                $subjectName = $grade->subject->subject_name;
                $total = 0;
                $count = 0;

                foreach ($this->components as $comp) {
                    $value = $grade->$comp;
                    if (!is_null($value)) {
                        $total += $value;
                        $count++;
                    }
                }

                // If no marks, treat as 0
                $avg = $count > 0 ? round($total / $count, 2) : 0;
                $subGrade = $avg > 0 ? $this->getGrade($avg) : 'F';

                $subjectMarks[$subjectName] = $avg;
                $subjectGrades[$subjectName] = $subGrade;
            }

            // Map only core subjects grades to points
            $pointsMap = ['A'=>1,'B'=>2,'C'=>3,'D'=>4,'E'=>5,'S'=>6,'F'=>7];
            $coreGrades = $studentGrades->whereIn('subject_id', $coreSubjects)
                ->map(fn($g) => $subjectGrades[$g->subject->subject_name] ?? 'F')
                ->toArray();

            $corePoints = array_map(fn($g) => $pointsMap[$g] ?? 7, $coreGrades);
            $totalCorePoints = array_sum($corePoints);

            $division = match(true) {
                $totalCorePoints >= 3 && $totalCorePoints <= 9 => 'Division I',
                $totalCorePoints >= 10 && $totalCorePoints <= 12 => 'Division II',
                $totalCorePoints >= 13 && $totalCorePoints <= 17 => 'Division III',
                $totalCorePoints >= 18 && $totalCorePoints <= 20 => 'Division IV',
                default => 'Division 0 / Fail',
            };

            $results->push([
                'student_name' => $student->full_name ?? $student->name ?? 'Unknown',
                'class' => $studentGrades->first()->class,
                'marks' => $subjectMarks,
                'grades' => $subjectGrades,
                'totalPoints' => $totalCorePoints,
                'division' => $division,
            ]);
        }

        // Sort by totalCorePoints ascending
        $sorted = $results->sortBy('totalPoints')->values();

        // Assign rank
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

        return collect($ranked);
    }


     public function exportReportBookPdf()
{
    $results = $this->getResults();

    if ($results->isEmpty()) {
        Notification::make()
            ->title('No results to export for report book.')
            ->warning()
            ->send();
        return;
    }

    // Get subjects for table headers
    $subjects = array_keys($results->first()['grades']);

    $pdf = Pdf::loadView('filament.pages.adv-results-report-book', [
        'results' => $results,
        'semester' => $this->semester,
        'class' => $this->class,
        'combination' => $this->combination_id ? Combination::find($this->combination_id)->name : 'All',
        'subjects' => $subjects,
        'components' => $this->components,
    ])->setPaper('a4', 'portrait'); // Portrait for report book

    return response()->streamDownload(function () use ($pdf) {
        echo $pdf->output();
    }, 'report-book-' . now()->format('Y-m-d') . '.pdf');
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

        $subjects = array_keys($results->first()['grades']);

        $combinationName = $this->combination_id ? Combination::find($this->combination_id)->name : 'All';

        $pdf = Pdf::loadView('filament.pages.adv-results-pdf', [
            'results' => $results,
            'semester' => $this->semester,
            'class' => $this->class,
            'combination' => $combinationName,
            'subjects' => $subjects,
            'components' => $this->components,
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'advanced-results-' . now()->format('Y-m-d') . '.pdf');
    }
}
