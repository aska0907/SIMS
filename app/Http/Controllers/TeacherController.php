<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TeacherSubjectClass;

class TeacherController extends Controller
{
 
public function dashboard() 
{
    $teacherId = Auth::id();

    $assignments = TeacherSubjectClass::with('subject')
        ->where('user_id', $teacherId)
        ->get();

    return view('dashboard', compact('assignments'));
}


}
