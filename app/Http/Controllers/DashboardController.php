<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\AdvStudent;
use App\Models\Subject;
use App\Models\Combination;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $studentsCount = class_exists(Student::class) ? Student::count() : 0;
        $advStudentsCount = class_exists(AdvStudent::class) ? AdvStudent::count() : 0;
        $subjectsCount = class_exists(Subject::class) ? Subject::count() : 0;
        $combinationsCount = class_exists(Combination::class) ? Combination::count() : 0;

        // Teachers: adjust filtering as needed
        if (Schema::hasTable('users')) {
            if (Schema::hasColumn('users', 'role')) {
                $teachersCount = User::where('role', 'teacher')->count();
            } else {
                $teachersCount = User::count();
            }
        } else {
            $teachersCount = 0;
        }

        return view('welcome', compact(
            'studentsCount',
            'advStudentsCount',
            'subjectsCount',
            'combinationsCount',
            'teachersCount'
        ));
    }
}
