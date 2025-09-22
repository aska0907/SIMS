<?php

namespace App\Filament\Pages;

use App\Models\Grade;
use App\Models\Subject;
use App\Models\Student;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.dashboard';

    public $overallBestStudents;
    public $overallWorstStudents;
    public $subjectPerformance;
    public $classPerformance;
    public $overallStats;
    public $genderStats;
    public $recentTrends;
    public $criticalAlerts;

    public function mount(): void
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData(): void
    {
        // Get current semester data (you might want to make this configurable)
        $currentSemester = 'Semester 1'; // or get from settings/config
        
        $grades = Grade::with(['student', 'subject'])
            ->where('semester', $currentSemester)
            ->get();

        if ($grades->isEmpty()) {
            $this->initializeEmptyData();
            return;
        }

        // Calculate overall student performance
        $this->calculateStudentPerformance($grades);
        
        // Calculate subject performance
        $this->calculateSubjectPerformance($grades);
        
        // Calculate class performance
        $this->calculateClassPerformance($grades);
        
        // Calculate overall statistics
        $this->calculateOverallStatistics($grades);
        
        // Calculate gender statistics
        $this->calculateGenderStatistics($grades);
        
        // Calculate recent trends
        $this->calculateRecentTrends($grades);
        
        // Generate critical alerts
        $this->generateCriticalAlerts();
    }

    private function calculateStudentPerformance($grades): void
    {
        // Group grades by student and calculate overall performance
        $studentPerformance = $grades->groupBy('student.id')->map(function ($studentGrades, $studentId) {
            $student = $studentGrades->first()->student;
            
            // Calculate overall score for each student across all subjects
            $overallScores = $studentGrades->map(function ($grade) {
                return $this->calculateOverallScore($grade);
            })->filter()->values();

            if ($overallScores->isEmpty()) {
                return null;
            }

            $averageScore = $overallScores->avg();
            $totalSubjects = $overallScores->count();
            $passedSubjects = $overallScores->filter(fn($score) => $score >= 50)->count();

            return [
                'student_id' => $studentId,
                'student_name' => $student->full_name ?? 'Unknown',
                'class' => $student->class ?? 'Unknown',
                'average_score' => round($averageScore, 2),
                'total_subjects' => $totalSubjects,
                'passed_subjects' => $passedSubjects,
                'pass_rate' => round(($passedSubjects / $totalSubjects) * 100, 1),
                'grade_letter' => $this->getGradeLetter($averageScore),
                'performance_category' => $this->getPerformanceCategory($averageScore),
            ];
        })->filter()->sortByDesc('average_score');

        // Get top 10 and bottom 10 students
        $this->overallBestStudents = $studentPerformance->take(10)->values();
        $this->overallWorstStudents = $studentPerformance->sortBy('average_score')->take(10)->values();
    }

    private function calculateSubjectPerformance($grades): void
    {
        $subjectPerformance = $grades->groupBy('subject_id')->map(function ($subjectGrades, $subjectId) {
            $subject = $subjectGrades->first()->subject;
            
            $scores = $subjectGrades->map(function ($grade) {
                return $this->calculateOverallScore($grade);
            })->filter();

            if ($scores->isEmpty()) {
                return null;
            }

            $totalStudents = $scores->count();
            $averageScore = $scores->avg();
            $passedStudents = $scores->filter(fn($score) => $score >= 50)->count();
            $highPerformers = $scores->filter(fn($score) => $score >= 80)->count();

            return [
                'subject_id' => $subjectId,
                'subject_name' => $subject->subject_name ?? 'Unknown',
                'total_students' => $totalStudents,
                'average_score' => round($averageScore, 2),
                'highest_score' => $scores->max(),
                'lowest_score' => $scores->min(),
                'pass_rate' => round(($passedStudents / $totalStudents) * 100, 1),
                'excellence_rate' => round(($highPerformers / $totalStudents) * 100, 1),
                'standard_deviation' => round($this->calculateStandardDeviation($scores), 2),
                'grade_distribution' => $this->calculateGradeDistribution($scores),
                'performance_trend' => $this->getPerformanceTrend($averageScore),
                'needs_attention' => $averageScore < 60 || ($passedStudents / $totalStudents) < 0.7,
            ];
        })->filter()->sortByDesc('average_score');

        $this->subjectPerformance = $subjectPerformance->values();
    }

    private function calculateClassPerformance($grades): void
    {
        $classPerformance = $grades->groupBy('class')->map(function ($classGrades, $className) {
            $scores = $classGrades->map(function ($grade) {
                return $this->calculateOverallScore($grade);
            })->filter();

            if ($scores->isEmpty()) {
                return null;
            }

            $totalRecords = $scores->count();
            $uniqueStudents = $classGrades->pluck('student.id')->unique()->count();
            $averageScore = $scores->avg();
            $passedRecords = $scores->filter(fn($score) => $score >= 50)->count();

            return [
                'class_name' => $className,
                'total_students' => $uniqueStudents,
                'total_records' => $totalRecords,
                'average_score' => round($averageScore, 2),
                'highest_score' => $scores->max(),
                'lowest_score' => $scores->min(),
                'pass_rate' => round(($passedRecords / $totalRecords) * 100, 1),
                'performance_level' => $this->getClassPerformanceLevel($averageScore),
            ];
        })->filter()->sortByDesc('average_score');

        $this->classPerformance = $classPerformance->values();
    }

    private function calculateOverallStatistics($grades): void
    {
        $allScores = $grades->map(function ($grade) {
            return $this->calculateOverallScore($grade);
        })->filter();

        if ($allScores->isEmpty()) {
            $this->overallStats = [];
            return;
        }

        $totalRecords = $allScores->count();
        $totalStudents = $grades->pluck('student.id')->unique()->count();
        $totalSubjects = $grades->pluck('subject_id')->unique()->count();
        
        $this->overallStats = [
            'total_students' => $totalStudents,
            'total_subjects' => $totalSubjects,
            'total_records' => $totalRecords,
            'overall_average' => round($allScores->avg(), 2),
            'highest_score' => $allScores->max(),
            'lowest_score' => $allScores->min(),
            'median_score' => round($this->calculateMedian($allScores), 2),
            'standard_deviation' => round($this->calculateStandardDeviation($allScores), 2),
            'pass_rate' => round(($allScores->filter(fn($s) => $s >= 50)->count() / $totalRecords) * 100, 1),
            'excellence_rate' => round(($allScores->filter(fn($s) => $s >= 80)->count() / $totalRecords) * 100, 1),
            'grade_distribution' => $this->calculateGradeDistribution($allScores),
            'performance_categories' => $this->calculatePerformanceCategories($allScores),
        ];
    }

    private function calculateGenderStatistics($grades): void
    {
        $maleGrades = $grades->filter(fn($g) => $g->student->gender === 'Male');
        $femaleGrades = $grades->filter(fn($g) => $g->student->gender === 'Female');
        
        if ($maleGrades->isEmpty() && $femaleGrades->isEmpty()) {
            $this->genderStats = null;
            return;
        }

        $maleScores = $maleGrades->map(fn($g) => $this->calculateOverallScore($g))->filter();
        $femaleScores = $femaleGrades->map(fn($g) => $this->calculateOverallScore($g))->filter();

        $this->genderStats = [
            'male' => [
                'count' => $maleGrades->pluck('student.id')->unique()->count(),
                'total_records' => $maleScores->count(),
                'average' => $maleScores->isNotEmpty() ? round($maleScores->avg(), 2) : 0,
                'pass_rate' => $maleScores->isNotEmpty() ? 
                    round(($maleScores->filter(fn($s) => $s >= 50)->count() / $maleScores->count()) * 100, 1) : 0,
                'excellence_rate' => $maleScores->isNotEmpty() ? 
                    round(($maleScores->filter(fn($s) => $s >= 80)->count() / $maleScores->count()) * 100, 1) : 0,
            ],
            'female' => [
                'count' => $femaleGrades->pluck('student.id')->unique()->count(),
                'total_records' => $femaleScores->count(),
                'average' => $femaleScores->isNotEmpty() ? round($femaleScores->avg(), 2) : 0,
                'pass_rate' => $femaleScores->isNotEmpty() ? 
                    round(($femaleScores->filter(fn($s) => $s >= 50)->count() / $femaleScores->count()) * 100, 1) : 0,
                'excellence_rate' => $femaleScores->isNotEmpty() ? 
                    round(($femaleScores->filter(fn($s) => $s >= 80)->count() / $femaleScores->count()) * 100, 1) : 0,
            ],
        ];
    }

    private function calculateRecentTrends($grades): void
    {
        $assessmentTrends = [];
        $assessments = ['test1', 'test2', 'mid_term', 'terminal'];
        
        foreach ($assessments as $assessment) {
            $scores = $grades->pluck($assessment)->filter(fn($score) => !is_null($score) && is_numeric($score));
            
            if ($scores->isNotEmpty()) {
                $assessmentTrends[$assessment] = [
                    'average' => round($scores->avg(), 2),
                    'count' => $scores->count(),
                    'pass_rate' => round(($scores->filter(fn($s) => $s >= 50)->count() / $scores->count()) * 100, 1),
                    'trend_indicator' => $this->getTrendIndicator($scores->avg()),
                ];
            }
        }

        $this->recentTrends = $assessmentTrends;
    }

    private function generateCriticalAlerts(): void
    {
        $alerts = [];
        
        // Alert for subjects with high failure rates
        foreach ($this->subjectPerformance as $subject) {
            if ($subject['pass_rate'] < 50) {
                $alerts[] = [
                    'type' => 'critical',
                    'category' => 'subject_performance',
                    'title' => 'High Failure Rate',
                    'message' => "{$subject['subject_name']} has a {$subject['pass_rate']}% pass rate. Immediate intervention needed.",
                    'action_required' => true,
                ];
            }
        }
        
        // Alert for classes with low performance
        foreach ($this->classPerformance as $class) {
            if ($class['average_score'] < 50) {
                $alerts[] = [
                    'type' => 'warning',
                    'category' => 'class_performance',
                    'title' => 'Low Class Performance',
                    'message' => "{$class['class_name']} has an average score of {$class['average_score']}. Review needed.",
                    'action_required' => true,
                ];
            }
        }
        
        // Alert for students at risk
        $atRiskStudents = $this->overallWorstStudents->filter(fn($student) => $student['average_score'] < 40);
        if ($atRiskStudents->count() > 0) {
            $alerts[] = [
                'type' => 'urgent',
                'category' => 'student_welfare',
                'title' => 'Students at Risk',
                'message' => "{$atRiskStudents->count()} students are performing critically low (below 40%). Immediate support required.",
                'action_required' => true,
            ];
        }

        $this->criticalAlerts = collect($alerts);
    }

    // Helper methods
    private function calculateOverallScore($grade): ?float
    {
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

    private function calculateMedian($scores): float
    {
        $sorted = $scores->sort()->values();
        $count = $sorted->count();
        
        if ($count % 2 == 0) {
            return ($sorted[$count/2 - 1] + $sorted[$count/2]) / 2;
        } else {
            return $sorted[floor($count/2)];
        }
    }

    private function calculateStandardDeviation($scores): float
    {
        $mean = $scores->avg();
        $squaredDifferences = $scores->map(fn($score) => pow($score - $mean, 2));
        return sqrt($squaredDifferences->avg());
    }

    private function calculateGradeDistribution($scores): array
    {
        $total = $scores->count();
        return [
            'A' => ['count' => $scores->filter(fn($s) => $s >= 90)->count(), 'percentage' => round(($scores->filter(fn($s) => $s >= 90)->count() / $total) * 100, 1)],
            'B' => ['count' => $scores->filter(fn($s) => $s >= 80 && $s < 90)->count(), 'percentage' => round(($scores->filter(fn($s) => $s >= 80 && $s < 90)->count() / $total) * 100, 1)],
            'C' => ['count' => $scores->filter(fn($s) => $s >= 70 && $s < 80)->count(), 'percentage' => round(($scores->filter(fn($s) => $s >= 70 && $s < 80)->count() / $total) * 100, 1)],
            'D' => ['count' => $scores->filter(fn($s) => $s >= 60 && $s < 70)->count(), 'percentage' => round(($scores->filter(fn($s) => $s >= 60 && $s < 70)->count() / $total) * 100, 1)],
            'E' => ['count' => $scores->filter(fn($s) => $s >= 50 && $s < 60)->count(), 'percentage' => round(($scores->filter(fn($s) => $s >= 50 && $s < 60)->count() / $total) * 100, 1)],
            'F' => ['count' => $scores->filter(fn($s) => $s < 50)->count(), 'percentage' => round(($scores->filter(fn($s) => $s < 50)->count() / $total) * 100, 1)],
        ];
    }

    private function calculatePerformanceCategories($scores): array
    {
        $total = $scores->count();
        return [
            'excellent' => ['count' => $scores->filter(fn($s) => $s >= 80)->count(), 'percentage' => round(($scores->filter(fn($s) => $s >= 80)->count() / $total) * 100, 1)],
            'good' => ['count' => $scores->filter(fn($s) => $s >= 70 && $s < 80)->count(), 'percentage' => round(($scores->filter(fn($s) => $s >= 70 && $s < 80)->count() / $total) * 100, 1)],
            'satisfactory' => ['count' => $scores->filter(fn($s) => $s >= 60 && $s < 70)->count(), 'percentage' => round(($scores->filter(fn($s) => $s >= 60 && $s < 70)->count() / $total) * 100, 1)],
            'needs_improvement' => ['count' => $scores->filter(fn($s) => $s >= 50 && $s < 60)->count(), 'percentage' => round(($scores->filter(fn($s) => $s >= 50 && $s < 60)->count() / $total) * 100, 1)],
            'failing' => ['count' => $scores->filter(fn($s) => $s < 50)->count(), 'percentage' => round(($scores->filter(fn($s) => $s < 50)->count() / $total) * 100, 1)],
        ];
    }

    private function getGradeLetter($score): string
    {
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';
        if ($score >= 50) return 'E';
        return 'F';
    }

    private function getPerformanceCategory($score): string
    {
        if ($score >= 80) return 'Excellent';
        if ($score >= 70) return 'Good';
        if ($score >= 60) return 'Satisfactory';
        if ($score >= 50) return 'Needs Improvement';
        return 'Failing';
    }

    private function getClassPerformanceLevel($average): string
    {
        if ($average >= 80) return 'Outstanding';
        if ($average >= 70) return 'Very Good';
        if ($average >= 60) return 'Good';
        if ($average >= 50) return 'Satisfactory';
        return 'Needs Improvement';
    }

    private function getPerformanceTrend($average): string
    {
        if ($average >= 75) return 'positive';
        if ($average >= 60) return 'stable';
        return 'concerning';
    }

    private function getTrendIndicator($average): string
    {
        if ($average >= 70) return 'up';
        if ($average >= 50) return 'stable';
        return 'down';
    }

    private function initializeEmptyData(): void
    {
        $this->overallBestStudents = collect();
        $this->overallWorstStudents = collect();
        $this->subjectPerformance = collect();
        $this->classPerformance = collect();
        $this->overallStats = [];
        $this->genderStats = null;
        $this->recentTrends = [];
        $this->criticalAlerts = collect();
    }

    // Public methods for use in the view
    public function getTopPerformingSubjects($limit = 5)
    {
        return $this->subjectPerformance->take($limit);
    }

    public function getSubjectsNeedingAttention($limit = 3)
    {
        return $this->subjectPerformance->where('needs_attention', true)->take($limit);
    }

    public function getHighRiskStudents()
    {
        return $this->overallWorstStudents->filter(fn($student) => $student['average_score'] < 40);
    }

    public function getExcellentPerformers()
    {
        return $this->overallBestStudents->filter(fn($student) => $student['average_score'] >= 80);
    }

    public function getCriticalAlertsCount()
    {
        return $this->criticalAlerts->where('type', 'critical')->count();
    }

    public function getUrgentAlertsCount()
    {
        return $this->criticalAlerts->where('type', 'urgent')->count();
    }

    public function refreshDashboard()
    {
        $this->loadDashboardData();
        
        // You can add a notification here if needed
        // Notification::make()
        //     ->title('Dashboard Refreshed')
        //     ->success()
        //     ->send();
    }
}