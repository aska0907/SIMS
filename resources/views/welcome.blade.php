<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Overview Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Laravel Vite Assets -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        :root {
            /* Brand Colors */
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #3b82f6;
            --secondary: #059669;
            --secondary-light: #10b981;
            --accent: #7c3aed;
            --warning: #d97706;
            --danger: #dc2626;
            
            /* Neutral Colors */
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            
            /* Background & Surface */
            --bg-primary: #ffffff;
            --bg-secondary: var(--gray-50);
            --surface: #ffffff;
            --surface-elevated: #ffffff;
            
            /* Shadows */
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            
            /* Border Radius */
            --radius-sm: 0.375rem;
            --radius: 0.5rem;
            --radius-md: 0.75rem;
            --radius-lg: 1rem;
            --radius-xl: 1.5rem;
            
            /* Transitions */
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg-secondary);
            color: var(--gray-900);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .main-container {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--gray-50) 0%, #e0e7ff 100%);
        }

        /* Header Styles */
        .header {
            background: var(--surface);
            border-bottom: 1px solid var(--gray-200);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 50;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link, .nav-button {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: var(--radius-md);
            font-weight: 500;
            font-size: 0.875rem;
            transition: var(--transition);
            text-decoration: none;
            color: var(--gray-700);
            background: transparent;
            border: 1px solid transparent;
            cursor: pointer;
        }

        .nav-link:hover, .nav-button:hover {
            background: var(--gray-100);
            color: var(--gray-900);
        }

        .nav-link.primary {
            background: var(--primary);
            color: white;
        }

        .nav-link.primary:hover {
            background: var(--primary-dark);
            color: white;
        }

        /* Dashboard Content */
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            font-size: 1.125rem;
            color: var(--gray-600);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-xl);
            padding: 1.5rem;
            transition: var(--transition-slow);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            border-color: var(--gray-300);
        }

        .stat-card.students::before { background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%); }
        .stat-card.advanced::before { background: linear-gradient(90deg, var(--secondary) 0%, var(--secondary-light) 100%); }
        .stat-card.subjects::before { background: linear-gradient(90deg, var(--warning) 0%, #f59e0b 100%); }
        .stat-card.combinations::before { background: linear-gradient(90deg, var(--danger) 0%, #f87171 100%); }
        .stat-card.teachers::before { background: linear-gradient(90deg, var(--accent) 0%, #8b5cf6 100%); }

        .stat-header {
            display: flex;
            items-center: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 3rem;
            height: 3rem;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stat-card.students .stat-icon { background: var(--primary); }
        .stat-card.advanced .stat-icon { background: var(--secondary); }
        .stat-card.subjects .stat-icon { background: var(--warning); }
        .stat-card.combinations .stat-icon { background: var(--danger); }
        .stat-card.teachers .stat-icon { background: var(--accent); }

        .stat-content {
            display: flex;
            flex-direction: column;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--gray-900);
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--gray-600);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.75rem;
            color: var(--secondary);
            margin-top: 0.5rem;
        }

        /* Charts Section */
        .charts-container {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .chart-card {
            background: var(--surface);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-xl);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
        }

        .chart-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .chart-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .chart-title i {
            color: var(--primary);
            font-size: 1.125rem;
        }

        .chart-actions {
            display: flex;
            gap: 0.5rem;
        }

        .chart-action {
            padding: 0.5rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            background: transparent;
            color: var(--gray-600);
            cursor: pointer;
            transition: var(--transition);
        }

        .chart-action:hover {
            background: var(--gray-100);
            color: var(--gray-800);
        }

        /* Student Distribution Chart */
        .student-chart-container {
            grid-column: 1 / -1;
            margin-bottom: 2rem;
        }

        /* Sidebar */
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .quick-actions {
            background: var(--surface);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-xl);
            padding: 1.5rem;
        }

        .quick-actions-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .action-button {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1rem;
            background: var(--surface);
            border: 1px solid var(--gray-300);
            border-radius: var(--radius-md);
            text-decoration: none;
            color: var(--gray-700);
            font-weight: 500;
            font-size: 0.875rem;
            transition: var(--transition);
            margin-bottom: 0.75rem;
        }

        .action-button:last-child {
            margin-bottom: 0;
        }

        .action-button:hover {
            background: var(--gray-50);
            border-color: var(--gray-400);
            transform: translateX(2px);
        }

        .action-button.primary {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .action-button.primary:hover {
            background: var(--primary-dark);
        }

        .action-button.secondary {
            background: var(--secondary);
            color: white;
            border-color: var(--secondary);
        }

        .action-button.secondary:hover {
            background: #047857;
        }

        /* System Info */
        .system-info {
            background: var(--surface);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-xl);
            padding: 1.5rem;
        }

        .system-info-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 1rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--gray-100);
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 0.875rem;
            color: var(--gray-600);
        }

        .info-value {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-900);
        }

        /* Chart Canvas */
        canvas {
            width: 100% !important;
            max-height: 400px !important;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .charts-container {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                grid-template-rows: auto;
                display: grid;
            }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1rem;
            }
            
            .header {
                padding: 1rem;
            }
            
            .header-content {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .page-title {
                font-size: 1.75rem;
            }
            
            .stat-number {
                font-size: 2rem;
            }
            
            .nav {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 480px) {
            .stat-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .stat-content {
                width: 100%;
            }
        }

        /* Loading States */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .skeleton {
            background: linear-gradient(90deg, var(--gray-200) 25%, var(--gray-100) 50%, var(--gray-200) 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Header -->
        <header class="header">
            <div class="header-content">
                <div class="logo">
                    <i class="fas fa-graduation-cap"></i>
                    SIMS
                </div>
                
                @if (Route::has('login'))
                    <nav class="nav">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="nav-link">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                            <form action="{{route('logout')}}" method="post" style="display: inline;">
                                @csrf
                                <button type="submit" class="nav-button">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="nav-link">
                                <i class="fas fa-sign-in-alt"></i>
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="nav-link primary">
                                    <i class="fas fa-user-plus"></i>
                                    Register
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </header>
        
        <!-- Main Content -->
        <div class="dashboard-container">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">System Overview</h1>
                <p class="page-subtitle">Comprehensive view of your educational management system</p>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card students">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $studentsCount ?? 0 }}</div>
                        <div class="stat-label">O-Level Students</div>
                        <div class="stat-trend">
                            <i class="fas fa-arrow-up"></i>
                            <span>Active enrollments</span>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card advanced">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $advStudentsCount ?? 0 }}</div>
                        <div class="stat-label">A-Level Students</div>
                        <div class="stat-trend">
                            <i class="fas fa-arrow-up"></i>
                            <span>Advanced level</span>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card subjects">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $subjectsCount ?? 0 }}</div>
                        <div class="stat-label">Available Subjects</div>
                        <div class="stat-trend">
                            <i class="fas fa-check"></i>
                            <span>Curriculum ready</span>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card combinations">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-link"></i>
                        </div>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $combinationsCount ?? 0 }}</div>
                        <div class="stat-label">Subject Combinations</div>
                        <div class="stat-trend">
                            <i class="fas fa-cogs"></i>
                            <span>Configured</span>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card teachers">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $teachersCount ?? 0 }}</div>
                        <div class="stat-label">System Users</div>
                        <div class="stat-trend">
                            <i class="fas fa-users"></i>
                            <span>Active accounts</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Distribution Chart -->
            <div class="chart-card student-chart-container">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-chart-pie"></i>
                        Student Distribution by Level
                    </h3>
                    <div class="chart-actions">
                        <button class="chart-action" title="Export">
                            <i class="fas fa-download"></i>
                        </button>
                        <button class="chart-action" title="Refresh">
                            <i class="fas fa-sync"></i>
                        </button>
                    </div>
                </div>
                <canvas id="studentChart"></canvas>
            </div>

            <!-- Charts and Sidebar -->
            <div class="charts-container">
                <!-- System Resources Chart -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title">
                            <i class="fas fa-chart-bar"></i>
                            System Resources Overview
                        </h3>
                        <div class="chart-actions">
                            <button class="chart-action" title="Export">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                    <canvas id="systemChart"></canvas>
                </div>
                
                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Quick Actions -->
                    <div class="quick-actions">
                        <h3 class="quick-actions-title">
                            <i class="fas fa-bolt"></i>
                            Quick Actions
                        </h3>
                        <div>
                            <a href="{{ route('dashboard') }}" class="action-button primary">
                                <i class="fas fa-chart-line"></i>
                                O-Level Results
                            </a>
                            <a href="{{ route('advanced-grades.index') }}" class="action-button secondary">
                                <i class="fas fa-medal"></i>
                                A-Level Results
                            </a>
                            <a href="#" class="action-button">
                                <i class="fas fa-users"></i>
                                Manage Students
                            </a>
                            <a href="#" class="action-button">
                                <i class="fas fa-book-open"></i>
                                Subject Management
                            </a>
                        </div>
                    </div>
                    
                    <!-- System Information -->
                    <div class="system-info">
                        <h3 class="system-info-title">System Information</h3>
                        <div class="info-item">
                            <span class="info-label">Last Updated</span>
                            <span class="info-value">{{ date('M d, Y') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">System Status</span>
                            <span class="info-value" style="color: var(--secondary);">
                                <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                                Online
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Total Records</span>
                            <span class="info-value">{{ ($studentsCount ?? 0) + ($advStudentsCount ?? 0) + ($subjectsCount ?? 0) + ($combinationsCount ?? 0) + ($teachersCount ?? 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Configuration
        const counts = {
            students: Number(@json($studentsCount ?? 0)),
            advStudents: Number(@json($advStudentsCount ?? 0)),
            subjects: Number(@json($subjectsCount ?? 0)),
            combinations: Number(@json($combinationsCount ?? 0)),
            teachers: Number(@json($teachersCount ?? 0)),
        };

        // Chart.js Global Configuration
        Chart.defaults.font.family = 'Inter, -apple-system, BlinkMacSystemFont, sans-serif';
        Chart.defaults.font.size = 12;
        Chart.defaults.color = '#6b7280';
        Chart.defaults.borderColor = '#e5e7eb';
        Chart.defaults.backgroundColor = 'rgba(37, 99, 235, 0.1)';

        // Enhanced color palette
        const colorPalette = {
            primary: '#2563eb',
            secondary: '#059669',
            warning: '#d97706',
            danger: '#dc2626',
            accent: '#7c3aed',
            gradients: {
                primary: ['rgba(37, 99, 235, 0.8)', 'rgba(59, 130, 246, 0.6)'],
                secondary: ['rgba(5, 150, 105, 0.8)', 'rgba(16, 185, 129, 0.6)'],
                tertiary: ['rgba(217, 119, 6, 0.8)', 'rgba(245, 158, 11, 0.6)'],
                quaternary: ['rgba(220, 38, 38, 0.8)', 'rgba(248, 113, 113, 0.6)'],
                quinary: ['rgba(124, 58, 237, 0.8)', 'rgba(139, 92, 246, 0.6)']
            }
        };

        // Student Distribution Chart (Enhanced Doughnut Chart)
        const studentCtx = document.getElementById('studentChart').getContext('2d');
        const totalStudents = counts.students + counts.advStudents;
        
        new Chart(studentCtx, {
            type: 'doughnut',
            data: {
                labels: ['O-Level Students', 'A-Level Students'],
                datasets: [{
                    data: [counts.students, counts.advStudents],
                    backgroundColor: colorPalette.gradients.primary.concat(colorPalette.gradients.secondary),
                    borderColor: [colorPalette.primary, colorPalette.secondary],
                    borderWidth: 3,
                    hoverOffset: 15,
                    cutout: '60%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 25,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: {
                                size: 14,
                                weight: '500'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        cornerRadius: 8,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                const percentage = totalStudents > 0 ? ((context.parsed / totalStudents) * 100).toFixed(1) : 0;
                                return `${context.label}: ${context.formattedValue} (${percentage}%)`;
                            }
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 1000
                }
            }
        });

        // System Resources Chart (Enhanced Bar Chart with gradient)
        const systemCtx = document.getElementById('systemChart').getContext('2d');
        
        // Create gradients for bars
        const gradient1 = systemCtx.createLinearGradient(0, 0, 0, 400);
        gradient1.addColorStop(0, colorPalette.gradients.tertiary[0]);
        gradient1.addColorStop(1, colorPalette.gradients.tertiary[1]);
        
        const gradient2 = systemCtx.createLinearGradient(0, 0, 0, 400);
        gradient2.addColorStop(0, colorPalette.gradients.quaternary[0]);
        gradient2.addColorStop(1, colorPalette.gradients.quaternary[1]);
        
        const gradient3 = systemCtx.createLinearGradient(0, 0, 0, 400);
        gradient3.addColorStop(0, colorPalette.gradients.quinary[0]);
        gradient3.addColorStop(1, colorPalette.gradients.quinary[1]);
        
        new Chart(systemCtx, {
            type: 'bar',
            data: {
                labels: ['Subjects', 'Combinations', 'Teachers'],
                datasets: [{
                    label: 'Count',
                    data: [counts.subjects, counts.combinations, counts.teachers],
                    backgroundColor: [gradient1, gradient2, gradient3],
                    borderColor: [colorPalette.warning, colorPalette.danger, colorPalette.accent],
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        cornerRadius: 8,
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            title: function(context) {
                                return context[0].label;
                            },
                            label: function(context) {
                                return `Total: ${context.formattedValue}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                weight: '500'
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return Number.isInteger(value) ? value : '';
                            }
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });

        // Add loading states and error handling
        document.addEventListener('DOMContentLoaded', function() {
            // Animate stat numbers on load
            const statNumbers = document.querySelectorAll('.stat-number');
            statNumbers.forEach(stat => {
                const finalValue = parseInt(stat.textContent);
                let currentValue = 0;
                const increment = Math.ceil(finalValue / 50);
                const timer = setInterval(() => {
                    currentValue += increment;
                    if (currentValue >= finalValue) {
                        currentValue = finalValue;
                        clearInterval(timer);
                    }
                    stat.textContent = currentValue;
                }, 30);
            });

            // Add click handlers for chart actions
            document.querySelectorAll('.chart-action').forEach(button => {
                button.addEventListener('click', function() {
                    const action = this.getAttribute('title');
                    if (action === 'Refresh') {
                        // Add refresh functionality
                        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                        setTimeout(() => {
                            this.innerHTML = '<i class="fas fa-sync"></i>';
                        }, 1000);
                    } else if (action === 'Export') {
                        // Add export functionality
                        console.log('Export functionality would be implemented here');
                    }
                });
            });
        });

        // Responsive chart resize
        window.addEventListener('resize', function() {
            Chart.helpers.each(Chart.instances, function(instance) {
                instance.resize();
            });
        });
    </script>
</body>
</html> '