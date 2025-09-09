<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Results Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1a56db;
            --secondary-color: #0d9488;
            --accent-color: #e11d48;
            --light-bg: #f9fafb;
            --border-color: #e5e7eb;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --division-I: #059669;
            --division-II: #2563eb;
            --division-III: #d97706;
            --division-IV: #6b7280;
            --division-0: #dc2626;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            background-color: #fff;
            padding: 1.5rem;
        }
        
        .report-container {
            max-width: 100%;
            margin: 0 auto;
            background: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            overflow: hidden;
        }
        
        .report-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.75rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .report-header::before {
            content: "";
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(-15deg);
            pointer-events: none;
        }
        
        .report-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
        }
        
        .report-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
        }
        
        .report-meta {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1.5rem;
            padding: 1.25rem 1.5rem;
            background-color: var(--light-bg);
            border-bottom: 1px solid var(--border-color);
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }
        
        .meta-item i {
            color: var(--secondary-color);
            font-size: 1.1rem;
        }
        
        .meta-item strong {
            color: var(--text-primary);
            margin-right: 0.25rem;
        }
        
        .report-content {
            padding: 1.5rem;
            overflow-x: auto;
        }
        
        .results-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.85rem;
            margin-top: 1rem;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .results-table th {
            background-color: #f8fafc;
            padding: 0.9rem 0.75rem;
            text-align: center;
            font-weight: 600;
            color: var(--text-primary);
            border-bottom: 2px solid var(--border-color);
            position: sticky;
            top: 0;
        }
        
        .results-table td {
            padding: 0.85rem 0.75rem;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
        }
        
        .results-table tr:last-child td {
            border-bottom: none;
        }
        
        .results-table tbody tr:hover {
            background-color: #f1f5f9;
        }
        
        .subject-header {
            background-color: #f1f5f9;
            font-weight: 600;
        }
        
        .rank-1 {
            background-color: #fffbeb;
            position: relative;
            font-weight: 600;
        }
        
        .rank-1::before {
            content: "🥇";
            position: absolute;
            left: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
        }
        
        .rank-2 {
            background-color: #f9fafb;
            position: relative;
            font-weight: 600;
        }
        
        .rank-2::before {
            content: "🥈";
            position: absolute;
            left: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
        }
        
        .division-I { 
            color: var(--division-I); 
            font-weight: 700; 
        }
        
        .division-II { 
            color: var(--division-II); 
            font-weight: 600; 
        }
        
        .division-III { 
            color: var(--division-III); 
            font-weight: 600; 
        }
        
        .division-IV { 
            color: var(--division-IV); 
        }
        
        .division-0 { 
            color: var(--division-0); 
            font-weight: 600; 
        }
        
        .points-cell {
            font-weight: 600;
            background-color: #f0f9ff;
        }
        
        .division-cell {
            font-weight: 600;
        }
        
        .report-footer {
            padding: 1.25rem 1.5rem;
            text-align: center;
            font-size: 0.8rem;
            color: var(--text-secondary);
            border-top: 1px solid var(--border-color);
            background-color: var(--light-bg);
        }
        
        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }
            
            .report-header {
                padding: 1.25rem 1rem;
            }
            
            .report-header h1 {
                font-size: 1.5rem;
            }
            
            .report-meta {
                flex-direction: column;
                gap: 0.75rem;
                padding: 1rem;
            }
            
            .results-table {
                font-size: 0.75rem;
            }
            
            .results-table th,
            .results-table td {
                padding: 0.6rem 0.4rem;
            }
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .report-container {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="report-container">
        <div class="report-header">
            <h1>Academic Results Report</h1>
            <p>{{ $semester }}</p>
        </div>
        
        <div class="report-meta">
            <div class="meta-item">
                <i class="fas fa-users"></i>
                <strong>Class:</strong> {{ $class === 'All' ? 'All Classes' : $class }}
            </div>
            <div class="meta-item">
                <i class="fas fa-chart-bar"></i>
                <strong>Included Marks:</strong> {{ implode(', ', array_map('ucfirst', $components)) }}
            </div>
            <div class="meta-item">
                <i class="fas fa-calendar-alt"></i>
                <strong>Generated On:</strong> {{ now()->format('M d, Y') }}
            </div>
        </div>
        
        <div class="report-content">
            <table class="results-table">
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Rank</th>
                        <th>Student</th>
                        @foreach($subjects as $subjectName)
                            <th colspan="2">{{ $subjectName }}</th>
                        @endforeach
                        <th>Points (Best 7)</th>
                        <th>Division</th>
                    </tr>
                    <tr class="subject-header">
                        <th colspan="3"></th>
                        @foreach($subjects as $subjectName)
                            <th>Score</th>
                            <th>Grade</th>
                        @endforeach
                        <th colspan="2"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $row)
                        <tr class="row-rank-{{ $row['rank'] == 1 ? '1' : ($row['rank'] == 2 ? '2' : '') }}">
                            <td>{{ $row['class'] }}</td>
                            <td>{{ $row['rank'] }}</td>
                            <td style="text-align: left;">{{ $row['student_name'] }}</td>
                            @foreach($subjects as $subjectName)
                                <td>{{ $row['marks'][$subjectName] ?? '-' }}</td>
                                <td>{{ $row['grades'][$subjectName] ?? '-' }}</td>
                            @endforeach
                            <td class="points-cell">{{ $row['totalPoints'] }}</td>
                            <td class="division-cell division-{{ str_replace(' ', '-', strtolower($row['division'])) }}">
                                {{ $row['division'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="report-footer">
            <p>Official Academic Report • Generated by School Management System</p>
        </div>
    </div>
</body>
</html>