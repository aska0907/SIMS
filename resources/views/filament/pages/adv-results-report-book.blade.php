<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Report Book</title>
    <style>
        /* Base styles */
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-gray: #f8f9fa;
            --border-color: #dee2e6;
            --text-color: #333;
            --success-color: #28a745;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            color: var(--text-color);
            background-color: #fff;
            line-height: 1.6;
        }
        
        /* Header styles */
        .report-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--border-color);
        }
        
        .report-header h1 {
            color: var(--secondary-color);
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 600;
        }
        
        .report-header h2 {
            color: var(--primary-color);
            margin: 0 0 20px 0;
            font-size: 22px;
            font-weight: 500;
        }
        
        .student-info {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 15px;
        }
        
        .info-item {
            background-color: var(--light-gray);
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 14px;
            display: flex;
            align-items: center;
        }
        
        .info-item strong {
            margin-right: 5px;
        }
        
        /* Table styles */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .report-table thead tr {
            background-color: var(--primary-color);
            color: white;
            text-align: left;
        }
        
        .report-table th,
        .report-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        
        .report-table tbody tr {
            border-left: 3px solid transparent;
            transition: all 0.2s;
        }
        
        .report-table tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
            border-left: 3px solid var(--primary-color);
        }
        
        .report-table tbody tr:last-of-type {
            border-bottom: 2px solid var(--primary-color);
        }
        
        .highlight-row {
            background-color: rgba(52, 152, 219, 0.1);
            font-weight: 600;
        }
        
        .grade-cell {
            font-weight: 600;
        }
        
        /* Summary section */
        .summary-section {
            margin-top: 30px;
            padding: 20px;
            background-color: var(--light-gray);
            border-radius: 8px;
            border-left: 4px solid var(--secondary-color);
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed var(--border-color);
        }
        
        .summary-item:last-child {
            border-bottom: none;
        }
        
        .summary-item strong {
            color: var(--secondary-color);
        }
        
        /* Page break handling */
        .student-page {
            page-break-inside: avoid;
            margin-bottom: 40px;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        /* Footer */
        .report-footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid var(--border-color);
            padding-top: 20px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .student-info {
                flex-direction: column;
                gap: 10px;
            }
            
            .report-table {
                font-size: 14px;
            }
            
            .report-table th,
            .report-table td {
                padding: 8px 10px;
            }
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .student-page {
                margin-bottom: 0;
            }
            
            .report-table {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>

@foreach($results as $row)
    <div class="student-page">
        <div class="report-header">
            <h1>Academic Report Book</h1>
            <h2>{{ $semester }}</h2>
            
            <div class="student-info">
                <div class="info-item">
                    <strong>Student:</strong> {{ $row['student_name'] }}
                </div>
                <div class="info-item">
                    <strong>Class:</strong> {{ $row['class'] }}
                </div>
                <div class="info-item">
                    <strong>Combination:</strong> {{ $combination }}
                </div>
            </div>
            
            <div class="info-item">
                <strong>Included Marks:</strong> {{ !empty($components) ? implode(', ', array_map('ucfirst', $components)) : '-' }}
            </div>
        </div>

        <table class="report-table">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Score</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subjects as $subjectName)
                    <tr>
                        <td>{{ $subjectName }}</td>
                        <td>{{ $row['marks'][$subjectName] ?? 0 }}</td>
                        <td class="grade-cell">{{ $row['grades'][$subjectName] ?? 'F' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary-section">
            <div class="summary-item">
                <strong>Total Points (Core)</strong>
                <span>{{ $row['totalPoints'] }}</span>
            </div>
            <div class="summary-item">
                <strong>Division</strong>
                <span>{{ $row['division'] }}</span>
            </div>
        </div>

        <div class="page-break"></div>
    </div>
@endforeach

<div class="report-footer">
    <p>Generated on {{ date('F j, Y') }} | Academic Reporting System</p>
</div>

</body>
</html>