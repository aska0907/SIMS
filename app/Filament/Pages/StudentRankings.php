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
    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $title = 'Subject Report';

    public ?string $semester = null;
    public ?string $class = 'All';
    public ?string $assessment = 'overall';
    public ?int $subject_id = null;
    public Collection $subjects;
    public $results;
    public $overallStats;

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

        $query = Grade::with(['student', 'subject'])
            ->where('semester', $state['semester']);

        if ($state['class'] && $state['class'] !== 'All') {
            $query->where('class', $state['class']);
        }

        if (!empty($state['subject_id'])) {
            $query->where('subject_id', $state['subject_id']);
        }

        $grades = $query->get();

        if ($grades->isEmpty()) {
            $this->results = collect();
            $this->overallStats = [];
            return;
        }

        $results = collect();
        $allScoresByClass = collect();
        $allScoresBySubject = collect();

        foreach ($grades->groupBy('class')->sortKeys() as $className => $classGrades) {
            foreach ($classGrades->groupBy('subject_id')->sortKeys() as $subjectId => $subjectGrades) {
                
                $subjectName = $subjectGrades->first()->subject->subject_name ?? 'Unknown';

                $studentsWithScores = $subjectGrades->map(function($grade) use ($state) {
                    $score = $this->calculateScore($grade, $state['assessment']);
                    return [
                        'grade' => $grade,
                        'score' => $score,
                        'student_name' => $grade->student->full_name ?? 'Unknown',
                        'student_id' => $grade->student->id ?? null,
                    ];
                })->filter(function($item) {
                    return !is_null($item['score']);
                });

                if ($studentsWithScores->isEmpty()) {
                    continue;
                }

                // Collect scores for overall analysis
                $allScoresByClass->put($className, 
                    $allScoresByClass->get($className, collect())->merge($studentsWithScores->pluck('score'))
                );
                $allScoresBySubject->put($subjectName, 
                    $allScoresBySubject->get($subjectName, collect())->merge($studentsWithScores->pluck('score'))
                );

                $topStudents = $studentsWithScores->sortByDesc('score')->take(10)->values();
                $bottomStudents = $studentsWithScores->sortBy('score')->take(10)->values();

                // Calculate comprehensive statistics
                $stats = $this->calculateComprehensiveStats($studentsWithScores, $state['assessment']);

                $results->push([
                    'class' => $className,
                    'subject' => $subjectName,
                    'top' => $topStudents,
                    'bottom' => $bottomStudents,
                    'stats' => $stats,
                ]);
            }
        }

        // Calculate overall statistics across all classes/subjects
        $this->overallStats = $this->calculateOverallStats($allScoresByClass, $allScoresBySubject, $grades);
        $this->results = $results;
    }

    private function calculateScore($grade, $assessmentType)
    {
        if ($assessmentType === 'overall') {
            $scores = collect([
                $grade->test1,
                $grade->test2,
                $grade->mid_term,
                $grade->terminal
            ])->filter(function($score) {
                return !is_null($score) && is_numeric($score);
            });

            return $scores->isEmpty() ? null : round($scores->avg(), 2);
        }

        $score = $grade->{$assessmentType};
        return (is_null($score) || !is_numeric($score)) ? null : $score;
    }

    private function calculateComprehensiveStats($studentsWithScores, $assessmentType)
    {
        $scores = $studentsWithScores->pluck('score');
        
        // Basic statistics
        $total = $scores->count();
        $sum = $scores->sum();
        $average = $scores->avg();
        $median = $this->calculateMedian($scores);
        $mode = $this->calculateMode($scores);
        $min = $scores->min();
        $max = $scores->max();
        
        // Spread measures
        $range = $max - $min;
        $variance = $this->calculateVariance($scores, $average);
        $standardDeviation = sqrt($variance);
        
        // Grade distributions
        $gradeDistribution = $this->calculateGradeDistribution($scores);
        
        // Performance categories
        $excellent = $scores->filter(fn($s) => $s >= 80)->count();
        $good = $scores->filter(fn($s) => $s >= 70 && $s < 80)->count();
        $satisfactory = $scores->filter(fn($s) => $s >= 60 && $s < 70)->count();
        $needsImprovement = $scores->filter(fn($s) => $s >= 50 && $s < 60)->count();
        $failing = $scores->filter(fn($s) => $s < 50)->count();
        
        // Quartiles
        $quartiles = $this->calculateQuartiles($scores);
        
        // Pass/Fail rates
        $passRate = ($scores->filter(fn($s) => $s >= 50)->count() / $total) * 100;
        $failRate = 100 - $passRate;
        
        return [
            'total_students' => $total,
            'sum' => round($sum, 2),
            'average' => round($average, 2),
            'median' => round($median, 2),
            'mode' => $mode,
            'min' => $min,
            'max' => $max,
            'range' => round($range, 2),
            'variance' => round($variance, 2),
            'standard_deviation' => round($standardDeviation, 2),
            'grade_distribution' => $gradeDistribution,
            'performance_categories' => [
                'excellent' => ['count' => $excellent, 'percentage' => round(($excellent/$total)*100, 1)],
                'good' => ['count' => $good, 'percentage' => round(($good/$total)*100, 1)],
                'satisfactory' => ['count' => $satisfactory, 'percentage' => round(($satisfactory/$total)*100, 1)],
                'needs_improvement' => ['count' => $needsImprovement, 'percentage' => round(($needsImprovement/$total)*100, 1)],
                'failing' => ['count' => $failing, 'percentage' => round(($failing/$total)*100, 1)],
            ],
            'quartiles' => $quartiles,
            'pass_rate' => round($passRate, 1),
            'fail_rate' => round($failRate, 1),
        ];
    }

    private function calculateOverallStats($scoresByClass, $scoresBySubject, $allGrades)
    {
        $classComparisons = $scoresByClass->map(function($scores, $className) {
            return [
                'class' => $className,
                'average' => round($scores->avg(), 2),
                'total_students' => $scores->count(),
                'highest' => $scores->max(),
                'lowest' => $scores->min(),
                'pass_rate' => round(($scores->filter(fn($s) => $s >= 50)->count() / $scores->count()) * 100, 1),
            ];
        })->sortByDesc('average');

        $subjectComparisons = $scoresBySubject->map(function($scores, $subjectName) {
            return [
                'subject' => $subjectName,
                'average' => round($scores->avg(), 2),
                'total_students' => $scores->count(),
                'highest' => $scores->max(),
                'lowest' => $scores->min(),
                'pass_rate' => round(($scores->filter(fn($s) => $s >= 50)->count() / $scores->count()) * 100, 1),
            ];
        })->sortByDesc('average');

        // Gender-based analysis (if gender data is available)
        $genderStats = $this->calculateGenderStats($allGrades);
        
        // Trend analysis (if historical data is needed)
        $trendAnalysis = $this->calculateTrendAnalysis($allGrades);

        return [
            'class_comparisons' => $classComparisons,
            'subject_comparisons' => $subjectComparisons,
            'gender_stats' => $genderStats,
            'trend_analysis' => $trendAnalysis,
            'total_students_analyzed' => $allGrades->pluck('student.id')->unique()->count(),
            'total_records' => $allGrades->count(),
        ];
    }

    private function calculateMedian($scores)
    {
        $sorted = $scores->sort()->values();
        $count = $sorted->count();
        
        if ($count % 2 == 0) {
            return ($sorted[$count/2 - 1] + $sorted[$count/2]) / 2;
        } else {
            return $sorted[floor($count/2)];
        }
    }

    private function calculateMode($scores)
    {
        $frequency = $scores->countBy();
        $maxFreq = $frequency->max();
        $modes = $frequency->filter(fn($freq) => $freq === $maxFreq)->keys();
        
        return $modes->count() === 1 ? $modes->first() : $modes->toArray();
    }

    private function calculateVariance($scores, $mean)
    {
        $squaredDifferences = $scores->map(fn($score) => pow($score - $mean, 2));
        return $squaredDifferences->avg();
    }

    private function calculateQuartiles($scores)
    {
        $sorted = $scores->sort()->values();
        $count = $sorted->count();
        
        return [
            'Q1' => $this->calculatePercentile($sorted, 25),
            'Q2' => $this->calculatePercentile($sorted, 50), // Median
            'Q3' => $this->calculatePercentile($sorted, 75),
        ];
    }

    private function calculatePercentile($sortedScores, $percentile)
    {
        $count = $sortedScores->count();
        $index = ($percentile / 100) * ($count - 1);
        
        if (floor($index) == $index) {
            return $sortedScores[$index];
        } else {
            $lower = floor($index);
            $upper = ceil($index);
            $weight = $index - $lower;
            return $sortedScores[$lower] * (1 - $weight) + $sortedScores[$upper] * $weight;
        }
    }

    private function calculateGradeDistribution($scores)
    {
        return [
            'A (90-100)' => $scores->filter(fn($s) => $s >= 90)->count(),
            'B (80-89)' => $scores->filter(fn($s) => $s >= 80 && $s < 90)->count(),
            'C (70-79)' => $scores->filter(fn($s) => $s >= 70 && $s < 80)->count(),
            'D (60-69)' => $scores->filter(fn($s) => $s >= 60 && $s < 70)->count(),
            'E (50-59)' => $scores->filter(fn($s) => $s >= 50 && $s < 60)->count(),
            'F (0-49)' => $scores->filter(fn($s) => $s < 50)->count(),
        ];
    }

    private function calculateGenderStats($grades)
    {
        // Assuming student model has gender field
        $maleGrades = $grades->filter(fn($g) => $g->student->gender === 'Male');
        $femaleGrades = $grades->filter(fn($g) => $g->student->gender === 'Female');
        
        if ($maleGrades->isEmpty() && $femaleGrades->isEmpty()) {
            return null;
        }

        return [
            'male' => [
                'count' => $maleGrades->count(),
                'average' => $maleGrades->avg('overall_score') ?? 0,
                'pass_rate' => $maleGrades->where('overall_score', '>=', 50)->count() / max($maleGrades->count(), 1) * 100,
            ],
            'female' => [
                'count' => $femaleGrades->count(),
                'average' => $femaleGrades->avg('overall_score') ?? 0,
                'pass_rate' => $femaleGrades->where('overall_score', '>=', 50)->count() / max($femaleGrades->count(), 1) * 100,
            ],
        ];
    }

    private function calculateTrendAnalysis($grades)
    {
        // Compare current performance with previous assessments
        $assessmentTrends = [];
        $assessments = ['test1', 'test2', 'mid_term', 'terminal'];
        
        foreach ($assessments as $assessment) {
            $scores = $grades->pluck($assessment)->filter();
            if ($scores->isNotEmpty()) {
                $assessmentTrends[$assessment] = [
                    'average' => round($scores->avg(), 2),
                    'count' => $scores->count(),
                    'improvement_potential' => 100 - $scores->avg(), // Room for improvement
                ];
            }
        }

        return $assessmentTrends;
    }

    // Helper methods for additional analytics
    public function getTopPerformingClasses($limit = 5)
    {
        return $this->overallStats['class_comparisons']->take($limit);
    }

    public function getSubjectsNeedingAttention($limit = 3)
    {
        return $this->overallStats['subject_comparisons']->sortBy('average')->take($limit);
    }

    public function getHighRiskStudents()
    {
        $highRisk = collect();
        foreach ($this->results as $result) {
            $failing = $result['bottom']->filter(fn($student) => $student['score'] < 50);
            $highRisk = $highRisk->merge($failing);
        }
        return $highRisk->unique('student_id');
    }

    public function getImprovementRecommendations()
    {
        $recommendations = [];
        
        foreach ($this->results as $result) {
            $stats = $result['stats'];
            
            if ($stats['fail_rate'] > 30) {
                $recommendations[] = [
                    'type' => 'high_failure_rate',
                    'class' => $result['class'],
                    'subject' => $result['subject'],
                    'message' => "High failure rate ({$stats['fail_rate']}%) in {$result['class']} {$result['subject']}. Consider additional support or curriculum review.",
                ];
            }
            
            if ($stats['standard_deviation'] > 20) {
                $recommendations[] = [
                    'type' => 'high_variance',
                    'class' => $result['class'],
                    'subject' => $result['subject'],
                    'message' => "High score variance in {$result['class']} {$result['subject']}. Students need more individualized attention.",
                ];
            }
            
            if ($stats['average'] < 60) {
                $recommendations[] = [
                    'type' => 'low_average',
                    'class' => $result['class'],
                    'subject' => $result['subject'],
                    'message' => "Low class average ({$stats['average']}) in {$result['class']} {$result['subject']}. Review teaching methods and materials.",
                ];
            }
        }
        
        return $recommendations;
    }
}