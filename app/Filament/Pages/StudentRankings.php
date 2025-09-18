<?php

namespace App\Filament\Pages;

use App\Models\Grade;
use App\Models\Subject;
use Filament\Forms;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class StudentRankings extends Page
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static string $view = 'filament.pages.student-rankings';
    protected static ?string $title = 'Student Rankings';

    public ?string $semester = null;
    public ?string $class = 'All';
    public ?string $assessment = 'overall';
    public ?int $subject_id = null; // new filter
    public Collection $subjects;
    public $results;

    public function mount(): void
    {
        $this->subjects = Subject::orderBy('subject_name')->get();
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('semester')
                ->label('Semester')
                ->options([
                    'Semester 1' => 'Semester 1',
                    'Semester 2' => 'Semester 2',
                ])
                ->required(),

            Forms\Components\Select::make('class')
                ->label('Class')
                ->options([
                    'All' => 'All Classes',
                    'Form 1' => 'Form 1',
                    'Form 2' => 'Form 2',
                    'Form 3' => 'Form 3',
                    'Form 4' => 'Form 4',
                ])
                ->required(),

            Forms\Components\Select::make('assessment')
                ->label('Assessment')
                ->options([
                    'test1' => 'Test 1',
                    'test2' => 'Test 2',
                    'mid_term' => 'Mid Term',
                    'terminal' => 'Terminal',
                    'overall' => 'Overall',
                ])
                ->required(),

            Forms\Components\Select::make('subject_id')
                ->label('Subject (Optional)')
                ->options($this->subjects->pluck('subject_name', 'id'))
                ->nullable()
                ->placeholder('All Subjects'),
        ]);
    }

    public function showRankings()
    {
        $this->form->validate();
        $state = $this->form->getState();

        $query = Grade::with(['student','subject'])
            ->where('semester', $state['semester']);

        if ($state['class'] && $state['class'] !== 'All') {
            $query->where('class', $state['class']);
        }

        if (!empty($state['subject_id'])) {
            $query->where('subject_id', $state['subject_id']);
        }

        $grades = $query->get();

        // Group by class first, then by subject
        $results = collect();

        foreach ($grades->groupBy('class')->sortKeys() as $className => $classGrades) {

            foreach ($classGrades->groupBy('subject_id')->sortKeys() as $subjectId => $subjectGrades) {

                $subjectName = $subjectGrades->first()->subject->subject_name ?? 'Unknown';

                // Top 10
                $top = $subjectGrades
                    ->sortByDesc(function($g) use ($state) {
                        return $state['assessment'] === 'overall'
                            ? collect([$g->test1,$g->test2,$g->mid_term,$g->terminal])->filter()->avg()
                            : $g->{$state['assessment']};
                    })
                    ->take(10);

                // Least 10
                $least = $subjectGrades
                    ->sortBy(function($g) use ($state) {
                        return $state['assessment'] === 'overall'
                            ? collect([$g->test1,$g->test2,$g->mid_term,$g->terminal])->filter()->avg()
                            : $g->{$state['assessment']};
                    })
                    ->take(10);

                $results->push([
                    'class' => $className,
                    'subject' => $subjectName,
                    'top' => $top,
                    'least' => $least,
                ]);
            }
        }

        $this->results = $results;
    }
}
