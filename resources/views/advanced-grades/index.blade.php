<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - Advanced Grades</title>
    <link href="{{ asset('dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="d-flex min-vh-100">
    <!-- Sidebar -->
    <nav class="bg-primary text-white p-4 flex-shrink-0" style="width: 250px;">
        <h3 class="mb-4">Dashboard</h3>
        <ul class="nav flex-column mb-auto">
           <li class="nav-item mb-2">
                <a href="{{route('dashboard')}}" class="nav-link text-white"><i class="fas fa-book me-2"></i> Subjects olevel</a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{route('advanced-grades.index')}}" class="nav-link text-white"><i class="fas fa-book me-2"></i> Subjects Advance</a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-warning alert-dismissible fade show">
                <strong>Validation Errors:</strong>
                <ul class="mb-0">
                    @foreach($errors->getMessages() as $field => $messages)
                        <li>
                            <span class="text-danger">{{ $field }}:</span>
                            <ul>
                                @foreach($messages as $msg)
                                    <li>{{ $msg }}</li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="mb-4">
            <h1 class="h3">Advanced Subjects Dashboard</h1>
            <p class="text-muted">Manage your advanced subjects and assign grades to students</p>
        </div>

        <!-- Assigned Advanced Subjects -->
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">My Assigned Advanced Subjects</h5></div>
            <ul class="list-group list-group-flush">
                @forelse($assignments as $assign)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">{{ $assign->subject->subject_name }}</h6>
                            <small class="text-muted">{{ $assign->class_level }} | {{ $assign->combination->name }}</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#gradeModal-{{ $assign->id }}">
                            <i class="fas fa-edit me-1"></i>Manage Grades
                        </button>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No advanced subjects assigned yet.</li>
                @endforelse
            </ul>
        </div>

@foreach($assignments as $assign)
    <div class="modal fade" id="gradeModal-{{ $assign->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        {{ $assign->subject->subject_name }} - {{ $assign->class_level }} | {{ $assign->combination->name }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <ul class="nav nav-tabs mb-3">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#sem1-{{ $assign->id }}">Semester 1</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#sem2-{{ $assign->id }}">Semester 2</button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Semester 1 -->
                      <div class="tab-pane fade show active" id="sem1-{{ $assign->id }}">
                            <form method="POST" action="{{ route('advanced-grades.store') }}">
                                @csrf
                                <input type="hidden" name="subject_id" value="{{ $assign->subject_id }}">
                                <input type="hidden" name="class" value="{{ $assign->class_level }}">
                                <input type="hidden" name="combination_id" value="{{ $assign->combination->id }}">
                                <input type="hidden" name="current_semester" value="sem1">

                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead>
                                            <tr>
                                                <th>Student ID</th>
                                                <th>Name</th>
                                                <th>Test 1</th>
                                                <th>Test 2</th>
                                                <th>Mid Term</th>
                                                <th>Terminal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($assign->students as $student)
                                                @php
                                                    $existing = $student->advGrades
                                                        ->where('subject_id', $assign->subject_id)
                                                        ->where('semester', 'sem1')
                                                        ->first();
                                                @endphp
                                                <tr>
                                                    <td>{{ $student->registration_id }}</td>
                                                    <td>{{ $student->full_name }}</td>
                                                    <td><input type="number" min="0" max="100" name="grades[{{ $student->id }}][test1]" value="{{ $existing->test1 ?? '' }}" class="form-control form-control-sm"></td>
                                                    <td><input type="number" min="0" max="100" name="grades[{{ $student->id }}][test2]" value="{{ $existing->test2 ?? '' }}" class="form-control form-control-sm"></td>
                                                    <td><input type="number" min="0" max="100" name="grades[{{ $student->id }}][midterm]" value="{{ $existing->mid_term ?? '' }}" class="form-control form-control-sm"></td>
                                                    <td><input type="number" min="0" max="100" name="grades[{{ $student->id }}][terminal]" value="{{ $existing->terminal ?? '' }}" class="form-control form-control-sm"></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="text-end mt-2">
                                    <button type="submit" class="btn btn-success">Save Semester 2</button>
                                </div>
                            </form>
                        </div>

                        <!-- Semester 2 -->
                        <div class="tab-pane fade" id="sem2-{{ $assign->id }}">
                            <form method="POST" action="{{ route('advanced-grades.store') }}">
                                @csrf
                                <input type="hidden" name="subject_id" value="{{ $assign->subject_id }}">
                                <input type="hidden" name="class" value="{{ $assign->class_level }}">
                                <input type="hidden" name="combination_id" value="{{ $assign->combination->id }}">
                                <input type="hidden" name="current_semester" value="sem2">

                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead>
                                            <tr>
                                                <th>Student ID</th>
                                                <th>Name</th>
                                                <th>Test 1</th>
                                                <th>Test 2</th>
                                                <th>Mid Term</th>
                                                <th>Terminal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($assign->students as $student)
                                                @php
                                                    $existing = $student->advGrades
                                                        ->where('subject_id', $assign->subject_id)
                                                        ->where('semester', 'sem2')
                                                        ->first();
                                                @endphp
                                                <tr>
                                                    <td>{{ $student->registration_id }}</td>
                                                    <td>{{ $student->full_name }}</td>
                                                    <td><input type="number" min="0" max="100" name="grades[{{ $student->id }}][test1]" value="{{ $existing->test1 ?? '' }}" class="form-control form-control-sm"></td>
                                                    <td><input type="number" min="0" max="100" name="grades[{{ $student->id }}][test2]" value="{{ $existing->test2 ?? '' }}" class="form-control form-control-sm"></td>
                                                    <td><input type="number" min="0" max="100" name="grades[{{ $student->id }}][midterm]" value="{{ $existing->mid_term ?? '' }}" class="form-control form-control-sm"></td>
                                                    <td><input type="number" min="0" max="100" name="grades[{{ $student->id }}][terminal]" value="{{ $existing->terminal ?? '' }}" class="form-control form-control-sm"></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="text-end mt-2">
                                    <button type="submit" class="btn btn-success">Save Semester 2</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach




    </div>
</div>

<script src="{{ asset('dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
