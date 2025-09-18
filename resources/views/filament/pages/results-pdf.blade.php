<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Academic Results Report</title>
    <style>
/* ==== RESET & PRINT-SAFE DEFAULTS ==== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: "Times New Roman", Georgia, serif; /* 👈 More formal for print */
    line-height: 1.5;
    color: #000;
    background: #fff;
    padding: 0;
    font-size: 11pt; /* 👈 Standard print size */
}

/* ==== PRINT STYLES ==== */
@media print {
    body {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .report-container {
        box-shadow: none !important;
        border-radius: 0 !important;
    }
    .no-print {
        display: none !important;
    }
}

/* ==== COLORS (PRINT-SAFE) ==== */
:root {
    --primary-color: #2E8B57;       /* SeaGreen — official, calm */
    --secondary-color: #2c5aa0;     /* Deep blue */
    --light-bg: #f5f5f5;
    --border-color: #333;
    --text-primary: #000;
    --text-secondary: #555;
    --division-I: #006400;          /* Dark Green */
    --division-II: #000080;         /* Navy */
    --division-III: #8B4513;        /* SaddleBrown */
    --division-IV: #444;
    --division-0: #8B0000;          /* DarkRed */
}

/* ==== CONTAINER ==== */
.report-container {
    max-width: 100%;
    margin: 0 auto;
    background: white;
    border: 1px solid #ccc;
    page-break-inside: avoid;
}

/* ==== HEADER ==== */
.report-header {
    background: var(--primary-color);
    color: white;
    padding: 18px 20px;
    text-align: center;
}

.report-header h1 {
    font-size: 18pt;
    font-weight: bold;
    letter-spacing: 0.5px;
    margin: 0 0 6px 0;
    font-family: "Georgia", serif;
}

.report-header p {
    font-size: 11pt;
    font-weight: normal;
    margin: 0;
    opacity: 0.95;
}

/* ==== META INFO ==== */
.report-meta {
    display: table;
    width: 100%;
    background: #f9f9f9;
    border-top: 2px solid var(--primary-color);
    border-bottom: 2px solid var(--primary-color);
    font-size: 10pt;
}

.report-meta-row {
    display: table-row;
}

.meta-cell {
    display: table-cell;
    padding: 8px 12px;
    border-right: 1px solid #eee;
    vertical-align: top;
}

.meta-cell:last-child {
    border-right: none;
}

.meta-cell strong {
    color: #000;
    font-weight: bold;
}

/* ==== TABLE ==== */
.results-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 10pt;
    margin: 15px 0 0 0;
    page-break-inside: avoid;
}

.results-table th,
.results-table td {
    padding: 8px 6px;
    text-align: center;
    border: 1px solid #333;
    vertical-align: middle;
}

.results-table th {
    background: #e6e6e6;
    font-weight: bold;
    color: #000;
    font-size: 10pt;
}

.results-table thead tr.subject-header th {
    background: #d9d9d9;
    font-weight: bold;
    font-size: 9pt;
    padding: 6px 4px;
}

.results-table tbody tr.rank-1 td {
    background: #e6ffe6; /* Light green for 1st rank */
    font-weight: bold;
}

.results-table tbody tr.rank-2 td {
    background: #f0f0ff; /* Light blue for 2nd rank */
    font-weight: bold;
}

.division-I { color: var(--division-I); font-weight: bold; }
.division-II { color: var(--division-II); font-weight: bold; }
.division-III { color: var(--division-III); font-weight: bold; }
.division-IV { color: var(--division-IV); }
.division-0 { color: var(--division-0); font-weight: bold; }

.points-cell {
    font-weight: bold;
    background: #ffffe0; /* Light yellow */
}

.division-cell {
    font-weight: bold;
}

/* Student name left-aligned */
.results-table td:nth-child(3) {
    text-align: left;
    padding-left: 10px;
    font-weight: 500;
}

/* ==== FOOTER ==== */
.report-footer {
    padding: 15px 20px;
    text-align: center;
    font-size: 9pt;
    color: #555;
    border-top: 1px solid #ccc;
    margin-top: 20px;
}

.report-footer p {
    margin: 0;
}
    </style>
</head>
<body>
    <div class="report-container">
        <!-- HEADER -->
        <div class="report-header">
            <h1>ACADEMIC RESULTS REPORT</h1>
            <p>{{ $semester }}</p>
        </div>

        <!-- META -->
        <div class="report-meta">
            <div class="report-meta-row">
                <div class="meta-cell">
                    <strong>CLASS:</strong> {{ $class === 'All' ? 'ALL CLASSES' : $class }}
                </div>
                <div class="meta-cell">
                    <strong>INCLUDED MARKS:</strong> {{ implode(', ', array_map('ucfirst', $components)) }}
                </div>
                <div class="meta-cell">
                    <strong>GENERATED ON:</strong> {{ now()->format('F d, Y') }}
                </div>
            </div>
        </div>

        <!-- TABLE -->
        <div class="report-content">
            <table class="results-table">
                <thead>
                    <tr>
                        <th>CLASS</th>
                        <th>RANK</th>
                        <th>STUDENT NAME</th>
                        @foreach($subjects as $subjectName)
                            <th colspan="2">{{ strtoupper($subjectName) }}</th>
                        @endforeach
                        <th>POINTS<br>(BEST 7)</th>
                        <th>DIVISION</th>
                    </tr>
                    <tr class="subject-header">
                        <th colspan="3"></th>
                        @foreach($subjects as $subjectName)
                            <th>SCORE</th>
                            <th>GRADE</th>
                        @endforeach
                        <th colspan="2"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $row)
                        <tr class="{{ $row['rank'] == 1 ? 'rank-1' : ($row['rank'] == 2 ? 'rank-2' : '') }}">
                            <td>{{ $row['class'] }}</td>
                            <td>{{ $row['rank'] }}</td>
                            <td>{{ $row['student_name'] }}</td>
                            @foreach($subjects as $subjectName)
                                <td>{{ $row['marks'][$subjectName]['average'] ?? '-' }}</td>
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

        <!-- FOOTER -->
        <div class="report-footer">
            <p>OFFICIAL ACADEMIC REPORT — GENERATED BY SCHOOL MANAGEMENT SYSTEM</p>
        </div>
    </div>
</body>
</html>