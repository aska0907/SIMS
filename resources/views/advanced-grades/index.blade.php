<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - Advanced Grades</title>
    <link href="{{ asset('dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1e40af;
            --primary-dark: #1e3a8a;
            --accent-color: #3b82f6;
            --light-bg: #f8fafc;
            --border-color: #e2e8f0;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
        }
        
        /* Sidebar Styling */
        .sidebar {
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            width: 280px;
            min-height: 100vh;
            box-shadow: 3px 0 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 1.5rem 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-header h3 {
            font-weight: 600;
            color: white;
            margin-bottom: 0;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 0.75rem 1.5rem;
            margin: 0.25rem 0.5rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            font-weight: 500;
        }
        
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(5px);
        }
        
        .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 10px;
        }
        
        .logout-btn {
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            color: rgba(255, 255, 255, 0.85);
        }
        
        .logout-btn:hover {
            color: white;
        }
        
        /* Main Content Styling */
        .main-content {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }
        
        .page-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .page-header h1 {
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
        }
        
        .page-header p {
            color: #64748b;
            font-size: 1.1rem;
        }
        
        /* Card Styling */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin-bottom: 1.5rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
            border-radius: 12px 12px 0 0 !important;
        }
        
        .card-header h5 {
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 0;
        }
        
        .list-group-item {
            padding: 1.25rem 1.5rem;
            border: none;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.2s ease;
        }
        
        .list-group-item:last-child {
            border-bottom: none;
            border-radius: 0 0 12px 12px;
        }
        
        .list-group-item:hover {
            background-color: #f8fafc;
        }
        
        .subject-name {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }
        
        .subject-details {
            color: #64748b;
            font-size: 0.9rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            border-radius: 6px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(30, 64, 175, 0.3);
        }
        
        /* Modal Styling */
        .modal-header {
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--accent-color) 100%);
            border-radius: 12px 12px 0 0;
            padding: 1.25rem 1.5rem;
        }
        
        .modal-title {
            font-weight: 600;
        }
        
        .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .nav-tabs {
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 1.5rem;
        }
        
        .nav-tabs .nav-link {
            color: #64748b;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px 8px 0 0;
            margin: 0 0.25rem;
            font-weight: 500;
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            background-color: transparent;
            border-bottom: 3px solid var(--primary-color);
            transform: none;
        }
        
        .nav-tabs .nav-link:hover {
            color: var(--primary-dark);
            background-color: rgba(30, 64, 175, 0.05);
            transform: none;
        }
        
        /* Table Styling */
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 600;
            border-bottom: 2px solid var(--border-color);
            padding: 1rem 0.75rem;
        }
        
        .table tbody td {
            padding: 0.75rem;
            vertical-align: middle;
            border-color: var(--border-color);
        }
        
        .form-control {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            transition: all 0.2s ease;
        }
        
        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .btn-success {
            background-color: var(--success-color);
            border: none;
            border-radius: 6px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .btn-success:hover {
            background-color: #0da271;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.3);
        }
        
        /* Alert Styling */
        .alert {
            border: none;
            border-radius: 8px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .alert-warning {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .btn-close:focus {
            box-shadow: none;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #94a3b8;
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
    </style>
</head>
<body>
<div class="d-flex min-vh-100">
    <!-- Sidebar -->
    <nav class="sidebar text-white flex-shrink-0">
        <div class="sidebar-header">
            <h3><i class="fas fa-chalkboard-teacher me-2"></i>Teacher Dashboard</h3>
        </div>
        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a href="{{route('dashboard')}}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" aria-label="O-Level Subjects">
                    <i class="fas fa-book"></i> Subjects O-Level
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('advanced-grades.index')}}" class="nav-link {{ request()->routeIs('advanced-grades.index') ? 'active' : '' }}" aria-label="Advanced Subjects">
                    <i class="fas fa-graduation-cap"></i> Subjects Advanced
                </a>
            </li>
            <li class="nav-item mt-4 pt-3 border-top border-white-10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link logout-btn" aria-label="Logout">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Validation Errors:</strong>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
                <ul class="mb-0 mt-2">
                    @foreach($errors->getMessages() as $field => $messages)
                        <li>
                            <span class="text-danger">{{ $field }}:</span>
                            <ul class="mb-2">
                                @foreach($messages as $msg)
                                    <li>{{ $msg }}</li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="page-header">
            <h1><i class="fas fa-graduation-cap me-2"></i>Advanced Subjects Dashboard</h1>
            <p>Manage your advanced subjects and assign grades to students</p>
        </div>

        <!-- Assigned Advanced Subjects -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>My Assigned Advanced Subjects</h5>
                <span class="badge bg-primary rounded-pill">{{ count($assignments) }}</span>
            </div>
            <ul class="list-group list-group-flush">
                @forelse($assignments as $assign)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="subject-name">{{ $assign->subject->subject_name }}</div>
                            <div class="subject-details">
                                <span class="badge bg-light text-dark me-2">{{ $assign->class_level }}</span>
                                <span class="badge bg-light text-dark">{{ $assign->combination->name }}</span>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#gradeModal-{{ $assign->id }}">
                            <i class="fas fa-edit me-1"></i>Manage Grades
                        </button>
                    </li>
                @empty
                    <li class="list-group-item empty-state">
                        <i class="fas fa-book-open"></i>
                        <div>No advanced subjects assigned yet.</div>
                    </li>
                @endforelse
            </ul>
        </div>

        @foreach($assignments as $assign)
            <div class="modal fade" id="gradeModal-{{ $assign->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-edit me-2"></i>
                                {{ $assign->subject->subject_name }} - {{ $assign->class_level }} | {{ $assign->combination->name }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#sem1-{{ $assign->id }}">
                                        <i class="fas fa-calendar-alt me-1"></i>Semester 1
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#sem2-{{ $assign->id }}">
                                        <i class="fas fa-calendar-alt me-1"></i>Semester 2
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content p-3">
                                <!-- Semester 1 -->
                                <div class="tab-pane fade show active" id="sem1-{{ $assign->id }}">
                                    <form method="POST" action="{{ route('advanced-grades.store') }}">
                                        @csrf
                                        <input type="hidden" name="subject_id" value="{{ $assign->subject_id }}">
                                        <input type="hidden" name="class" value="{{ $assign->class_level }}">
                                        <input type="hidden" name="combination_id" value="{{ $assign->combination->id }}">
                                        <input type="hidden" name="current_semester" value="sem1">

                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle">
                                                <thead class="table-light">
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
                                                            <td class="fw-semibold">{{ $student->registration_id }}</td>
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

                                        <div class="text-end mt-3">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-save me-1"></i>Save Semester 1
                                            </button>
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
                                            <table class="table table-hover align-middle">
                                                <thead class="table-light">
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
                                                            <td class="fw-semibold">{{ $student->registration_id }}</td>
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

                                        <div class="text-end mt-3">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-save me-1"></i>Save Semester 2
                                            </button>
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