<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Academic Results - {{ $semester }}</title>
    <style>
        @page {
            margin: 15mm;
            size: A3 landscape;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 9px;
            line-height: 1.2;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #2563eb;
        }
        
        .header h1 {
            color: #2563eb;
            margin: 0 0 5px 0;
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .header .subtitle {
            font-size: 16px;
            color: #1e40af;
            margin: 5px 0;
            font-weight: 600;
        }
        
        .header .meta {
            font-size: 10px;
            color: #6b7280;
            margin: 2px 0;
        }
        
        .filters-section {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 20px;
        }
        
        .filters-title {
            font-size: 12px;
            font-weight: bold;
            color: #374151;
            margin: 0 0 8px 0;
            text-transform: uppercase;
        }
        
        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .filter-item {
            font-size: 9px;
            color: #4b5563;
        }
        
        .filter-item strong {
            color: #1f2937;
        }
        
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 8px;
        }
        
        .results-table th,
        .results-table td {
            border: 0.5px solid #d1d5db;
            padding: 4px 3px;
            text-align: center;
            vertical-align: middle;
        }
        
        .results-table th {
            background: #f3f4f6;
            font-weight: bold;
            color: #374151;
            text-transform: uppercase;
            font-size: 7px;
        }
        
        .results-table .header-main {
            background: #e5e7eb;
            font-size: 8px;
        }
        
        .results-table .student-name {
            text-align: left;
            max-width: 80px;
            word-wrap: break-word;
            font-size: 8px;
        }
        
        .results-table .class-cell {
            font-weight: 600;
            background: #fafafa;
        }
        
        .rank-1 {
            background-color: #fef3c7 !important;
            font-weight: bold;
        }
        
        .rank-2 {
            background-color: #f3f4f6 !important;
            font-weight: 600;
        }
        
        .rank-3 {
            background-color: #fef2f2 !important;
            font-weight: 600;
        }
        
        .rank-cell {
            font-weight: bold;
            font-size: 9px;
        }
        
        .points-cell {
            font-weight: bold;
            font-size: 9px;
            background: #f9fafb;
        }
        
        .grade-cell {
            font-weight: bold;
            font-size: 8px;
        }
        
        .mark-cell {
            font-size: 8px;
        }
        
        .division-badge {
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .division-1 { 
            background: #dcfce7; 
            color: #166534; 
            border: 1px solid #bbf7d0;
        }
        
        .division-2 { 
            background: #dbeafe; 
            color: #1d4ed8; 
            border: 1px solid #bfdbfe;
        }
        
        .division-3 { 
            background: #fef3c7; 
            color: #ca8a04; 
            border: 1px solid #fed7aa;
        }
        
        .division-4 { 
            background: #fee2e2; 
            color: #dc2626; 
            border: 1px solid #fecaca;
        }
        
        .division-fail { 
            background: #f3f4f6; 
            color: #6b7280; 
            border: 1px solid #d1d5db;
        }
        
        .summary-section {
            margin-top: 15px;
            padding: 10px;
            background: #f8fafc;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }
        
        .summary-title {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #374151;
        }
        
        .summary-stats {
            display: flex;
            justify-content: space-around;
            font-size: 9px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 14px;
            font-weight: bold;
            color: #2563eb;
        }
        
        .stat-label {
            color: #6b7280;
            text-transform: uppercase;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 8px;
            color: #6b7280;
        }
        
        .grading-scale {
            margin-top: 15px;
            font-size: 8px;
        }
        
        .grading-scale h4 {
            margin: 0 0 5px 0;
            font-size: 9px;
            color: #374151;
        }
        
        .scale-row {
            display: inline-block;
            margin-right: 15px;
            margin-bottom: 3px;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #6b7280;
        }
        
        /* Print optimizations */
        .page-break {
            page-break-after: always;
        }
        
        .avoid-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    {{-- Header Section --}}
    <div class="header">
        <h1>{{ $school_name }}</h1>
        <div class="subtitle">Academic Results Report</div>
        <div class="meta">{{ $semester }} | Generated on {{ $generated_at }}</div>
    </div>

    {{-- Filters Section --}}
    <div class="filters-section">
        <div class="filters-title">Report Parameters</div>
        <div class="filter-row">
            <div class="filter-item">
                <strong>Semester:</strong> {{ $semester }}
            </div>
            <div class="filter-item">
                <strong>Class:</strong> {{ $class }}
            </div>
            <div class="filter-item">
                <strong>Assessment Components:</strong> 
                @if(!empty($components))
                    {{ implode(', ', $components) }}
                @else
                    Not specified
                @endif
            </div>
            <div class="filter-item">
                <strong>Total Students:</strong> {{ $results->count() }}
            </div>
        </div>
    </div>

    {{-- Results Table --}}
    @if($results->isNotEmpty())
        <div class="avoid-break">
            <table class="results-table">
                <thead>
                    <tr class="header-main">
                        <th rowspan="2" style="width: 60px;">Class</th>
                        <th rowspan="2" style="width: 40px;">Rank</th>
                        <th rowspan="2" style="width: 120px;">Student Name</th>
                        
                        {{-- Dynamic subjects header --}}
                        @foreach($subjects as $subjectName)
                            <th colspan="2" style="min-width: 60px;">
                                {{ Str::limit($subjectName, 15) }}
                            </th>
                        @endforeach
                        
                        <th rowspan="2" style="width: 50px;">Points<br/>(Best 7)</th>
                        <th rowspan="2" style="width: 80px;">Division</th>
                    </tr>
                    <tr>
                        @foreach($subjects as $subjectName)
                            <th style="width: 30px;">Score</th>
                            <th style="width: 30px;">Grade</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $index => $row)
                        <tr class="{{ 
                            $row['rank'] == 1 ? 'rank-1' : 
                            ($row['rank'] == 2 ? 'rank-2' : 
                            ($row['rank'] == 3 ? 'rank-3' : '')) 
                        }}">
                            <td class="class-cell">{{ $row['class'] }}</td>
                            <td class="rank-cell">{{ $row['rank'] }}</td>
                            <td class="student-name">{{ $row['student_name'] }}</td>
                            
                            {{-- Dynamic subjects marks/grades per student --}}
                            @foreach($subjects as $subjectName)
                                <td class="mark-cell">
                                    {{ isset($row['marks'][$subjectName]) && $row['marks'][$subjectName] !== null ? number_format($row['marks'][$subjectName], 1) : '-' }}
                                </td>
                                <td class="grade-cell">
                                    {{ $row['grades'][$subjectName] ?? '-' }}
                                </td>
                            @endforeach
                            
                            <td class="points-cell">{{ $row['totalPoints'] }}</td>
                            <td>
                                <span class="division-badge {{ 
                                    $row['division'] == 'Division I' ? 'division-1' : 
                                    ($row['division'] == 'Division II' ? 'division-2' : 
                                    ($row['division'] == 'Division III' ? 'division-3' : 
                                    ($row['division'] == 'Division IV' ? 'division-4' : 'division-fail'))) 
                                }}">
                                    {{ str_replace('Division ', 'Div ', $row['division']) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Summary Statistics --}}
        <div class="summary-section">
            <div class="summary-title">Report Summary</div>
            <div class="summary-stats">
                <div class="stat-item">
                    <div class="stat-number">{{ $results->where('division', 'Division I')->count() }}</div>
                    <div class="stat-label">Division I</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $results->where('division', 'Division II')->count() }}</div>
                    <div class="stat-label">Division II</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $results->where('division', 'Division III')->count() }}</div>
                    <div class="stat-label">Division III</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $results->where('division', 'Division IV')->count() }}</div>
                    <div class="stat-label">Division IV</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $results->where('division', 'Division 0 / Fail')->count() }}</div>
                    <div class="stat-label">Failed</div>
                </div>
            </div>
        </div>

        {{-- Grading Scale --}}
        <div class="grading-scale">
            <h4>Grading Scale:</h4>
            <span class="scale-row"><strong>A:</strong> 75-100%</span>
            <span class="scale-row"><strong>B:</strong> 65-74%</span>
            <span class="scale-row"><strong>C:</strong> 50-64%</span>
            <span class="scale-row"><strong>D:</strong> 30-49%</span>
            <span class="scale-row"><strong>F:</strong> Below 30%</span>
            |
            <span class="scale-row"><strong>Div I:</strong> 7-17 pts</span>
            <span class="scale-row"><strong>Div II:</strong> 18-21 pts</span>
            <span class="scale-row"><strong>Div III:</strong> 22-25 pts</span>
            <span class="scale-row"><strong>Div IV:</strong> 26-33 pts</span>
        </div>

    @else
        <div class="no-data">
            <h3>No results available</h3>
            <p>Please ensure you have selected appropriate filters and that data exists for the selected criteria.</p>
        </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <p><strong>{{ $school_name }}</strong> | Academic Results Report</p>
        <p>This is a computer-generated document. No signature required. | {{ $generated_at }}</p>
    </div>
</body>
</html>