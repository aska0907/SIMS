<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - Individual Grade Management</title>
    <link href="{{ asset('dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --card-shadow: 0 10px 30px rgba(0,0,0,0.1);
            --card-hover-shadow: 0 15px 40px rgba(0,0,0,0.15);
            --border-radius: 15px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        /* Enhanced Sidebar */
        .bg-primary {
            background: var(--primary-gradient) !important;
            box-shadow: 2px 0 20px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .bg-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="40" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="80" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .bg-primary > * {
            position: relative;
            z-index: 1;
        }

        .bg-primary h3 {
            font-weight: 700;
            font-size: 1.8rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .nav-link {
            padding: 1rem 1.5rem !important;
            margin: 0.5rem 0 !important;
            border-radius: var(--border-radius) !important;
            transition: var(--transition) !important;
            border: 1px solid transparent !important;
            backdrop-filter: blur(10px);
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.2) !important;
            transform: translateX(5px);
            border-color: rgba(255,255,255,0.3) !important;
        }

        /* Enhanced Main Content */
        .flex-grow-1 {
            background: transparent;
        }

        /* Enhanced Page Header */
        .mb-4 h1 {
            color: #2d3748;
            font-weight: 700;
            font-size: 2.2rem;
        }

        .mb-4 p {
            color: #718096;
            font-size: 1.1rem;
        }

        /* Enhanced Cards */
        .card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            border: none;
            transition: var(--transition);
            overflow: hidden;
        }

        .card:hover {
            box-shadow: var(--card-hover-shadow);
            transform: translateY(-2px);
        }

        .card-header {
            background: linear-gradient(135deg, #f8f9ff 0%, #e6f2ff 100%);
            border-bottom: 2px solid #e2e8f0;
            padding: 1.5rem 2rem;
        }

        .card-header h5 {
            color: #2d3748;
            font-weight: 600;
            font-size: 1.3rem;
            margin: 0;
        }

        /* Enhanced List Items */
        .list-group-item {
            padding: 1.5rem 2rem;
            border-color: #f1f5f9;
            transition: var(--transition);
        }

        .list-group-item:hover {
            background: linear-gradient(135deg, #f8faff 0%, #f0f7ff 100%);
            transform: translateX(5px);
        }

        .list-group-item h6 {
            color: #2d3748;
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .list-group-item small {
            color: #718096;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .list-group-item small::before {
            content: '\f0c0';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
        }

        /* Enhanced Buttons */
        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 25px;
            font-weight: 500;
            transition: var(--transition);
            font-size: 0.9rem;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-success {
            background: var(--success-gradient);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 500;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 172, 254, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-1px);
        }

        /* Enhanced Alerts */
        .alert {
            border: none;
            border-radius: var(--border-radius);
            padding: 1.2rem 1.5rem;
            margin-bottom: 1.5rem;
            animation: slideInDown 0.5s ease-out;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
            border-left: 4px solid #ffc107;
        }

        /* Enhanced Modal */
        .modal-content {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }

        .modal-header {
            background: var(--primary-gradient);
            color: white;
            padding: 1.5rem 2rem;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            border-bottom: none;
        }

        .modal-title {
            font-weight: 600;
            font-size: 1.3rem;
        }

        .modal-body {
            padding: 0;
        }

        /* Enhanced Tabs */
        .nav-tabs {
            border-bottom: 2px solid #e2e8f0;
            background: #f8fafc;
            padding: 1rem 2rem 0;
        }

        .nav-tabs .nav-link {
            color: #64748b !important;
            border: none !important;
            padding: 1rem 2rem !important;
            margin: 0 0.5rem !important;
            border-radius: 10px 10px 0 0 !important;
            font-weight: 500 !important;
            transition: var(--transition) !important;
            transform: none !important;
        }

        .nav-tabs .nav-link.active {
            background: white !important;
            color: #667eea !important;
            border-bottom: 3px solid #667eea !important;
            transform: translateY(-2px) !important;
        }

        .nav-tabs .nav-link:hover {
            background: rgba(102, 126, 234, 0.1) !important;
            transform: translateY(-1px) !important;
        }

        /* Enhanced Tab Content */
        .tab-pane {
            padding: 2rem;
        }

        /* Enhanced Table */
        .table {
            margin: 0;
        }

        .table th {
            background: linear-gradient(135deg, #f8f9ff 0%, #e6f2ff 100%);
            color: #2d3748;
            font-weight: 600;
            padding: 1.2rem 1rem;
            border: none;
            text-align: center;
            font-size: 0.95rem;
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #f1f5f9;
        }

        .table td:first-child {
            font-weight: 600;
            color: #667eea;
        }

        .table td:nth-child(2) {
            font-weight: 500;
            color: #2d3748;
        }

        /* Enhanced Form Controls */
        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.6rem 0.8rem;
            transition: var(--transition);
            text-align: center;
            font-weight: 500;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-control-sm {
            font-size: 0.9rem;
            padding: 0.5rem 0.7rem;
        }

        /* Enhanced Modal Footer */
        .modal-footer {
            background: #f8fafc;
            padding: 1.5rem 2rem;
            border-top: 2px solid #e2e8f0;
        }

        /* Animations */
        @keyframes slideInDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Page Load Animation */
        .card, .mb-4 {
            animation: fadeInUp 0.6s ease-out;
            animation-fill-mode: both;
        }

        .card:nth-child(1) { animation-delay: 0.1s; }
        .card:nth-child(2) { animation-delay: 0.2s; }
        .card:nth-child(3) { animation-delay: 0.3s; }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .bg-primary {
                width: 100% !important;
                min-height: auto;
            }
            
            .flex-grow-1 {
                padding: 1rem !important;
            }
            
            .card-header, .list-group-item, .tab-pane {
                padding: 1rem !important;
            }

            .modal-dialog {
                margin: 0.5rem;
            }

            .table-responsive {
                font-size: 0.85rem;
            }
        }

        /* Empty State Styling */
        .list-group-item.text-muted {
            text-align: center;
            padding: 3rem;
            color: #718096 !important;
            font-style: italic;
        }

        .list-group-item.text-muted::before {
            content: '\f02d';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 2rem;
            display: block;
            margin-bottom: 1rem;
            color: #cbd5e0;
        }
    </style>
</head>
<body>
<div class="d-flex min-vh-100">
    <!-- Sidebar -->
    <nav class="bg-primary text-white p-4 flex-shrink-0" style="width: 250px;">
        <h3 class="mb-4"><i class="fas fa-chalkboard-teacher me-2"></i>Dashboard</h3>
        <ul class="nav flex-column mb-auto">
            <li class="nav-item mb-2">
                <a href="#" class="nav-link text-white"><i class="fas fa-home me-2"></i>Dashboard</a>
            </li>
        </ul>
        <a href="#" class="text-white mt-auto d-flex align-items-center"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
    </nav>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-warning alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="mb-4">
            <h1 class="h3"><i class="fas fa-graduation-cap me-3"></i>Teacher Dashboard</h1>
            <p class="text-muted">Manage your subjects and assign grades to individual students with ease</p>
        </div>

        <!-- Assigned Subjects -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-books me-2"></i>My Assigned Subjects</h5>
            </div>
            <ul class="list-group list-group-flush">
                @forelse($assignments as $assign)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">{{ $assign->subject->subject_name }}</h6>
                            <small class="text-muted">{{ $assign->class }}</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#gradeModal-{{ $assign->id }}">
                            <i class="fas fa-edit me-1"></i>Manage Grades
                        </button>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No subjects assigned yet.</li>
                @endforelse
            </ul>
        </div>

        <!-- Grade Modals -->
        @foreach($assignments as $assign)
        <div class="modal fade" id="gradeModal-{{ $assign->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-calculator me-2"></i>{{ $assign->subject->subject_name }} - {{ $assign->class }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <ul class="nav nav-tabs mb-3">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#sem1-{{ $assign->id }}">
                                    <i class="fas fa-calendar-alt me-2"></i>Semester 1
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#sem2-{{ $assign->id }}">
                                    <i class="fas fa-calendar-alt me-2"></i>Semester 2
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Semester 1 -->
                            <div class="tab-pane fade show active" id="sem1-{{ $assign->id }}">
                                <form method="POST" action="{{ route('grades.store') }}">
                                    @csrf
                                    <input type="hidden" name="subject_id" value="{{ $assign->subject_id }}">
                                    <input type="hidden" name="class" value="{{ $assign->class }}">
                                    <input type="hidden" name="current_semester" value="sem1">

                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle">
                                            <thead>
                                                <tr>
                                                    <th>Student ID</th>
                                                    <th>Name</th>
                                                    <th>Test 1<br><small class="text-muted">(100)</small></th>
                                                    <th>Test 2<br><small class="text-muted">(100)</small></th>
                                                    <th>Mid Term<br><small class="text-muted">(100)</small></th>
                                                    <th>Terminal<br><small class="text-muted">(100)</small></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($assign->students() as $student)
                                                    @php
                                                        $existing = $student->grades->where('subject_id',$assign->subject_id)->where('semester','Semester 1')->first();
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
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save me-2"></i>Save Semester 1
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Semester 2 -->
                            <div class="tab-pane fade" id="sem2-{{ $assign->id }}">
                                <form method="POST" action="{{ route('grades.store') }}">
                                    @csrf
                                    <input type="hidden" name="subject_id" value="{{ $assign->subject_id }}">
                                    <input type="hidden" name="class" value="{{ $assign->class }}">
                                    <input type="hidden" name="current_semester" value="sem2">

                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle">
                                            <thead>
                                                <tr>
                                                    <th>Student ID</th>
                                                    <th>Name</th>
                                                    <th>Test 1<br><small class="text-muted">(100)</small></th>
                                                    <th>Test 2<br><small class="text-muted">(100)</small></th>
                                                    <th>Mid Term<br><small class="text-muted">(100)</small></th>
                                                    <th>Terminal<br><small class="text-muted">(100)</small></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($assign->students() as $student)
                                                    @php
                                                        $existing = $student->grades->where('subject_id',$assign->subject_id)->where('semester','Semester 2')->first();
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
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save me-2"></i>Save Semester 2
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- JS to track current semester -->
        <script>
            document.querySelectorAll('#gradeModal-{{ $assign->id }} .nav-link').forEach(link => {
                link.addEventListener('shown.bs.tab', function(e){
                    const sem = e.target.getAttribute('data-bs-target').replace('#','').split('-')[0];
                    document.getElementById('current_semester_{{ $assign->id }}').value = sem;
                });
            });
        </script>
        @endforeach
    </div>
</div>

<script src="{{ asset('dist/js/bootstrap.bundle.min.js') }}"></script>
<script>
    // Enhanced UI interactions
    document.addEventListener('DOMContentLoaded', function() {
        // Add input focus effects
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.style.transform = 'scale(1)';
            });
        });
    });
</script>
</body>
</html>