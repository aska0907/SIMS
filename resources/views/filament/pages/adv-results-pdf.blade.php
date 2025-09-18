<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Advanced Level Results Report</title>
    <style>
        /* ==== RESET & PRINT SAFE ==== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Times New Roman", Georgia, serif;
            color: #000;
            background: #fff;
            padding: 0;
            font-size: 11pt;
            line-height: 1.5;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        /* ==== COLORS ==== */
        :root {
            --primary: #2E8B57;        /* SeaGreen — official & academic */
            --secondary: #2c5aa0;      /* Deep blue */
            --light: #f8f9fa;
            --border: #333;
            --rank1: #e6ffe6;          /* Light green */
            --rank2: #f0f0ff;          /* Light blue */
            --division-I: #006400;     /* Dark Green */
            --division-II: #000080;    /* Navy */
            --division-III: #8B4513;   /* SaddleBrown */
            --division-IV: #444;
            --division-0: #8B0000;     /* DarkRed */
        }

        /* ==== CONTAINER ==== */
        .report-container {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            page-break-inside: avoid;
        }

        /* ==== HEADER ==== */
        .report-header {
            background: var(--primary);
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 1px;
        }

        .report-header h1 {
            font-size: 18pt;
            font-weight: bold;
            letter-spacing: 1px;
            margin: 0 0 8px 0;
            text-transform: uppercase;
        }

        .report-header h2 {
            font-size: 14pt;
            font-weight: normal;
            margin: 0;
            opacity: 0.95;
        }

        /* ==== META INFO ==== */
        .report-meta {
            background: #f9f9f9;
            padding: 12px 20px;
            border-top: 3px solid var(--primary);
            border-bottom: 3px solid var(--primary);
            font-size: 10pt;
            text-align: center;
            line-height: 1.6;
        }

        .meta-line {
            margin: 4px 0;
        }

        .meta-label {
            font-weight: bold;
            color: #000;
        }

        /* ==== TABLE ==== */
        .results-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            margin: 20px 0 0 0;
            page-break-inside: avoid;
        }

        .results-table th {
            background: #e6e6e6;
            border: 1px solid var(--border);
            padding: 8px 6px;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        .results-table td {
            border: 1px solid var(--border);
            padding: 8px 6px;
            text-align: center;
            vertical-align: middle;
        }

        /* Subject group headers */
        .results-table thead tr:first-child th {
            background: #d9d9d9;
            font-size: 10pt;
            text-transform: uppercase;
        }

        /* Sub-header row */
        .results-table thead tr:nth-child(2) th {
            background: #f2f2f2;
            font-size: 9pt;
            padding: 6px 4px;
        }

        /* Rank styling */
        .row-rank-1 {
            background: var(--rank1);
            font-weight: bold;
        }

        .row-rank-2 {
            background: var(--rank2);
            font-weight: bold;
        }

        /* Division coloring */
        .division-I { color: var(--division-I); font-weight: bold; }
        .division-II { color: var(--division-II); font-weight: bold; }
        .division-III { color: var(--division-III); font-weight: bold; }
        .division-IV { color: var(--division-IV); }
        .division-0 { color: var(--division-0); font-weight: bold; }

        /* Student name alignment */
        .results-table tbody td:nth-child(3) {
            text-align: left;
            padding-left: 10px;
            font-weight: 500;
        }

        /* ==== FOOTER ==== */
        .report-footer {
            margin-top: 30px;
            padding: 15px 20px;
            text-align: center;
            font-size: 9pt;
            color: #555;
            border-top: 1px solid #ccc;
        }

        .report-footer p {
            margin: 0;
        }

        /* ==== PAGE BREAK ==== */
        @page {
            margin: 0.5in;
            size: A4;
        }

        /* ==== WATERMARK (Optional) ==== */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 80pt;
            color: rgba(0,0,0,0.03);
            pointer-events: none;
            z-index: -1;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Optional watermark -->
    <div class="watermark">OFFICIAL</div>

    <div class="report-container">
        <!-- HEADER -->
        <div class="report-header">
            <h1>ADVANCED LEVEL RESULTS REPORT</h1>
            <h2>{{ $semester }}</h2>
        </div>

        <!-- META INFO -->
        <div class="report-meta">
            <div class="meta-line">
                <span class="meta-label">CLASS:</span> {{ $class === 'All' ? 'ALL CLASSES' : $class }} |
                <span class="meta-label">COMBINATION:</span> {{ $combination }}
            </div>
            <div class="meta-line">
                <span class="meta-label">INCLUDED MARKS:</span> {{ implode(', ', array_map('ucfirst', $components)) }}
            </div>
            <div class="meta-line">
                <span class="meta-label">GENERATED ON:</span> {{ now()->format('F d, Y') }}
            </div>
        </div>

        <!-- TABLE -->
        <table class="results-table">
            <thead>
                <tr>
                    <th>CLASS</th>
                    <th>RANK</th>
                    <th>STUDENT NAME</th>
                    @foreach($subjects as $subjectName)
                        <th colspan="2">{{ strtoupper($subjectName) }}</th>
                    @endforeach
                    <th>TOTAL<br>POINTS</th>
                    <th>DIVISION</th>
                </tr>
                <tr>
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
                    <tr class="row-rank-{{ $row['rank'] == 1 ? '1' : ($row['rank'] == 2 ? '2' : '') }}">
                        <td>{{ $row['class'] }}</td>
                        <td>
                            @if($row['rank'] == 1)
                                1st 
                            @elseif($row['rank'] == 2)
                                2nd 
                            @else
                                {{ $row['rank'] }}
                            @endif
                        </td>
                        <td>{{ $row['student_name'] }}</td>
                        @foreach($subjects as $subjectName)
                            <td>{{ $row['marks'][$subjectName]['average'] ?? '-' }}</td>
                            <td class="division-{{ str_replace(' ', '-', strtolower($row['grades'][$subjectName] ?? '0')) }}">
                                {{ $row['grades'][$subjectName] ?? '-' }}
                            </td>
                        @endforeach
                        <td>{{ $row['totalPoints'] }}</td>
                        <td class="division-{{ str_replace(' ', '-', strtolower($row['division'])) }}">
                            {{ $row['division'] }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- FOOTER -->
        <div class="report-footer">
            <p>OFFICIAL ACADEMIC DOCUMENT — CONFIDENTIAL</p>
            <p>Generated by School Management System • {{ now()->format('F d, Y') }}</p>
        </div>
    </div>
</body>
</html>