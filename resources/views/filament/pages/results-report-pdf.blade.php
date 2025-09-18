<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>O-Level Report Card</title>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        padding: 8px; /* -2px */
        font-size: 12px; /* -1px */
    }

    .report-card {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        border: 2px solid #2E8B57;
        box-shadow: 0 0 5px rgba(0,0,0,0.1);
    }

    .semester-info {
        background: #2196F3;
        color: white;
        text-transform: uppercase;
        padding: 6px 10px; /* -2px padding */
        margin-bottom: 4px; /* -1px */
        text-align: center;
        font-weight: bold;
        font-size: 15px; /* -1px */
        letter-spacing: 0.5px;
    }

    /* ==== STUDENT INFO ==== */
    .student-info {
        display: flex;
        padding: 8px; /* -1px */
        background: #e3f2fd;
        align-items: center;
    }

    .student-details {
        flex: 1;
    }

    .student-name {
        background: #ffeb3b;
        padding: 5px 6px; /* -1px vertical */
        font-size: 15px; /* -1px */
        font-weight: bold;
        margin-bottom: 4px; /* -1px */
        text-align: center;
        border-radius: 3px; /* -1px */
    }

    .info-row {
        display: flex;
        justify-content: center;
        gap: 18px; /* -2px */
        margin-bottom: 5px; /* -1px */
        font-size: 12px; /* -1px */
        color: #333;
        line-height: 1.3; /* -0.1 */
    }

    .info-label {
        font-weight: 600;
        color: #555;
        margin-right: 3px; /* -1px */
    }

    /* ==== MARKS TABLE ==== */
    .marks-table {
        width: 100%;
        border-collapse: collapse;
        margin: 6px 0; /* -3px */
    }

    .marks-table th,
    .marks-table td {
        border: 1px solid #ccc; /* lighter border */
        padding: 4px 3px; /* -2px vertical, -2px horizontal */
        text-align: center;
        font-size: 10px;
        line-height: 1.2;
    }

    .marks-table th {
        background: #4CAF50;
        color: white;
        font-weight: bold;
        font-size: 10px;
    }

    .subject-column {
        background: #e3f2fd;
        font-weight: bold;
        text-align: left !important;
        padding-left: 6px; /* -2px */
    }

    .ca-score { background: #fff3e0; }
    .mid-score { background: #f3e5f5; }
    .terminal-score { background: #e8f5e8; }

    .teacher-remarks {
        text-align: left !important;
        font-size: 9px;
        padding: 3px 4px; /* -1px all around */
        max-width: 80px; /* -5px */
        word-wrap: break-word;
        background: #fafafa;
    }

    .total-score {
        background: #ffeb3b;
        font-weight: bold;
    }

    .grade-column {
        background: #e1f5fe;
        font-weight: bold;
        font-size: 11px; /* -1px */
    }

    .grade-A { color: #4CAF50; font-weight: bold; }
    .grade-B { color: #2196F3; font-weight: bold; }
    .grade-C { color: #FF9800; font-weight: bold; }
    .grade-D { color: #f44336; font-weight: bold; }
    .grade-F { color: #d32f2f; font-weight: bold; }

    /* ==== ASSESSMENT TITLE ==== */
    .assessment-title {
        background: #4CAF50;
        color: white;
        padding: 6px 10px; /* -2px padding */
        text-align: center;
        font-weight: bold;
        font-size: 12px; /* -1px */
        margin: 8px 0 8px 0; /* -2px top/bottom */
        letter-spacing: 0.5px;
    }

    /* ==== ASSESSMENT GRID ==== */
    .assessment-grid {
        display: table;
        background: #fff8e1;
        width: 98%;
        table-layout: fixed;
        margin: 0 8px; /* -2px */
        border: 1px solid #ddd; /* lighter */
        border-collapse: collapse;
    }

    .assessment-grid-row {
        display: table-row;
    }

    .assessment-grid-cell {
        display: table-cell;
        padding: 6px 4px; /* -2px all around */
        text-align: center;
        border-right: 1px solid #eee;
        border-bottom: 1px solid #eee;
        font-size: 11px; /* -1px */
        vertical-align: middle;
        line-height: 1.2; /* -0.1 */
    }

    .assessment-grid-cell:nth-child(2n) {
        border-right: none;
    }

    .assessment-grid .label {
        font-weight: bold;
        color: #2E8B57;
        font-size: 10px; /* -1px */
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .assessment-grid .value {
        font-weight: bold;
        font-size: 13px; /* -1px */
        color: #000;
        line-height: 1.2; /* -0.1 */
    }

    /* ==== REMARKS ==== */
    .remarks-section {
        margin: 8px 8px; /* -2px all around */
        font-size: 11px; /* -1px */
        border-top: 2px solid #4CAF50; /* -1px */
        padding-top: 10px; /* -2px */
    }

    .remarks-title {
        font-weight: bold;
        font-size: 11px; /* -1px */
        color: #2E8B57;
        margin: 8px 0 4px 0; /* -2px top */
        padding-left: 6px; /* -2px */
        border-left: 3px solid #2E8B57; /* -1px */
    }

    .remarks-text {
        font-style: italic;
        font-size: 10px; /* -1px */
        line-height: 1.4; /* -0.1 */
        color: #333;
        background: #fff8e1;
        padding: 6px 8px; /* -2px all around */
        border-radius: 3px;
        margin-bottom: 8px; /* -2px */
        border-left: 2px solid #ffc107; /* -1px */
    }

    /* ==== SIGNATURES ==== */
    .signatures-container {
        margin: 25px 8px 8px 5px; /* -3px top, -2px bottom */
        display: table;
        width: 100%;
        table-layout: fixed;
    }

    .signature-cell {
        display: table-cell;
        vertical-align: top;
        width: 50%;
        padding: 0 6px; /* -2px */
    }

    .signature-line-box {
        width: 100%;
        height: 35px; /* -5px */
        border: 1px solid #ccc;
        margin: 6px 0; /* -2px */
        background: #f9f9f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px; /* -1px */
        color: #666;
        border-radius: 2px; /* -1px */
    }

    .signature-label {
        font-weight: bold;
        font-size: 10px; /* -1px */
        display: block;
        text-align: center;
        margin-top: 4px; /* -1px */
    }

    .contact-info {
        font-size: 9px;
        color: #666;
        margin-top: 4px; /* -1px */
        text-align: center;
    }

    .school-info {
        font-size: 9px;
        line-height: 1.3;
        color: #333;
        text-align: right;
        padding-right: 5px;
    }

    @media print {
        body {
            background: white;
            padding: 0;
            font-size: 11px;
        }
        .report-card {
            box-shadow: none;
            page-break-inside: avoid;
        }
        .student-photo img {
            max-width: 100%;
        }
    }

    @page {
        margin: 0.4in; /* tighter margin for print */
        size: A4;
    }

    /* ==== HEADER ==== */
    .header-table {
        display: table;
        width: 100%;
        table-layout: fixed;
        background: linear-gradient(135deg, #4CAF50, #2E8B57);
        color: white;
        padding: 10px 15px; /* -2px padding */
        box-sizing: border-box;
    }

    .header-cell {
        display: table-cell;
        vertical-align: middle;
        text-align: center;
    }

    .photo-cell { width: 70px; }
    .name-cell { width: auto; }
    .logo-cell { width: 70px; }

    .school-name {
        color: black;
        font-size: 16px; /* -1px */
        font-weight: bold;
        line-height: 1.2; /* -0.1 */
        text-align: center;
    }

    .student-photo {
        width: 70px; /* -5px */
        height: 70px; /* -5px */
        margin: 0 auto;
        overflow: hidden;
        border-radius: 4px; /* -2px */
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f5f5f5;
        box-shadow: 0 1px 2px rgba(0,0,0,0.08);
    }

    .student-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .student-photo.logo {
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 1px 2px rgba(0,0,0,0.08);
    }
</style>
</head>
<body>
    @foreach($results as $row)
        <div class="report-card">
      <!-- Header -->
<div class="header-table">
    <div class="header-cell photo-cell">
        <div class="student-photo">
            @if(isset($row['profile_picture']) && $row['profile_picture'])
                <img src="{{ public_path('storage/' . $row['profile_picture']) }}" alt="Student Photo">
            @else
                <div style="font-size: 10px; line-height: 1.1; text-align: center;">PHOTO</div>
            @endif
        </div>
    </div>
    <div class="header-cell name-cell">
        <div class="school-name {{ strlen($school->name ?? '') > 35 ? 'long-name' : '' }}">
            {{ $school ? $school->name : 'School Name' }}
           
        </div>
    </div>
    <div class="header-cell logo-cell">
        <div class="student-photo logo">
            @if($school && $school->logo)
                <img src="{{ public_path('storage/' . $school->logo) }}" alt="School Logo">
            @else
                <div style="font-size: 8px; line-height: 1.1; text-align: center;">LOGO</div>
            @endif
        </div>
    </div>
</div>
            <!-- Semester Banner -->
            <div class="semester-info">
                STUDENT'S  {{ $row['class'] ?? '___' }} SEMISTER REPORT
            </div>

            <!-- Student Information -->
            <div class="student-info">
                <div class="student-details">
                    <div class="student-name">{{ $row['student_name'] ?? '-' }}</div>
                  
                </div>
            </div>

<!-- Marks Table -->
<table class="marks-table">
    <thead>
        <tr>
            <th rowspan="2">SUBJECT</th>
            @foreach($components as $component)
                <th rowspan="2">{{ strtoupper(str_replace('_', ' ', $component)) }}</th>
            @endforeach
            <th rowspan="2">SUBJECT TEACHER REMARKS</th>
            <th colspan="4">GRADING</th>
        </tr>
        <tr>
            <th>Total</th>
            <th>Out Of</th>
            <th>AVERAGE</th>
            <th>GRADE</th>
        </tr>
    </thead>
    <tbody>
        @foreach($row['grades'] as $subjectName => $grade)
            @php
                // Determine sentence remark based on grade
                switch (strtoupper($grade)) {
                    case 'A':
                        $remarkSentence = "Excellent performance.";
                        break;
                    case 'B':
                        $remarkSentence = "Very good effort, keep it up.";
                        break;
                    case 'C':
                        $remarkSentence = "Good, but there is room for improvement.";
                        break;
                    case 'D':
                        $remarkSentence = "Needs improvement, work harder.";
                        break;
                    default:
                        $remarkSentence = "Poor performance, more effort required.";
                }

                // Combine teacher remark with grade remark
                $teacherRemark = $row['remarks'][$subjectName] ?? '';
                if($teacherRemark) {
                    $fullRemark = $teacherRemark . ' ' . $remarkSentence;
                } else {
                    $fullRemark = $remarkSentence;
                }
            @endphp
            <tr>
                <td class="subject-column">{{ $subjectName }}</td>
                @foreach($components as $component)
                    <td class="ca-score">{{ $row['marks'][$subjectName][$component] ?? '-' }}</td>
                @endforeach
                <td class="teacher-remarks">{{ $fullRemark }}</td>
                <td class="total-score">{{ $row['marks'][$subjectName]['total'] ?? '-' }}</td>
                <td>{{ $row['marks'][$subjectName]['overallOutOf'] ?? '-' }}</td>
                <td>{{ $row['marks'][$subjectName]['average'] ?? '-' }}</td>
                <td class="grade-{{ strtoupper($grade) }} grade-column">{{ $grade }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- ASSESSMENT -->
<!-- ASSESSMENT -->
<div class="assessment-title">
    STUDENT OVERALL ACADEMIC PERFORMANCE
</div>

<div class="assessment-grid">
    <div class="assessment-grid-row">
        <div class="assessment-grid-cell">
            <div class="label">WASTANI</div>
            <div class="value">{{ $row['average'] ?? '-' }}</div>
        </div>
        <div class="assessment-grid-cell">
            <div class="label">GRADE</div>
            <div class="value">
                @php
                    $average = $row['average'] ?? 0;
                    if ($average >= 75) {
                        $grade = 'A';
                    } elseif ($average >= 65) {
                        $grade = 'B';
                    } elseif ($average >= 50) {
                        $grade = 'C';
                    } elseif ($average >= 30) {
                        $grade = 'D';
                    } else {
                        $grade = 'F';
                    }
                @endphp
                {{ $grade }}
            </div>
        </div>
    </div>

    <div class="assessment-grid-row">
        <div class="assessment-grid-cell">
            <div class="label">DIVISION</div>
            <div class="value">{{ $row['division'] ?? '-' }}</div>
        </div>
        <div class="assessment-grid-cell">
            <div class="label">POINTS</div>
            <div class="value">{{ $row['points'] ?? '-' }}</div>
        </div>
    </div>

    <div class="assessment-grid-row">
        <div class="assessment-grid-cell">
            <div class="label">NAFASI</div>
            <div class="value">{{ $row['rank'] ?? '-' }}</div>
        </div>
        <div class="assessment-grid-cell">
            <div class="label">KATI YA</div>
            <div class="value">{{ $row['out_of'] ?? '-' }}</div>
        </div>
    </div>
</div>


<!-- REMARKS -->
@php
    $overall = $row['average'] ?? 0;
    if ($overall >= 75) {
        $autoRemark = "Excellent performance. Keep it up!";
    } elseif ($overall >= 65) {
        $autoRemark = "Very good effort, keep striving for excellence.";
    } elseif ($overall >= 50) {
        $autoRemark = "Good, but there is room for improvement.";
    } elseif ($overall >= 30) {
        $autoRemark = "Needs improvement, work harder.";
    } else {
        $autoRemark = "Poor performance, more effort required.";
    }
@endphp

<div class="remarks-title">Class Teacher's Remarks</div>
<div class="remarks-text">{{ $autoRemark }}</div>

<div class="remarks-title">Academic Master's Remarks</div>
<div class="remarks-text">{{ $autoRemark }}</div>

<div class="remarks-title">Headmaster's Remarks</div>
<div class="remarks-text">{{ $autoRemark }}</div>

<!-- SIGNATURES -->
<div class="signatures-container">
    <div class="signature-cell signature-left">
        <div class="signature-line-box">HEADMASTER'S SIGNATURE & SEAL</div>
        <div class="signature-label">HEADMASTER</div>
        <div class="contact-info">{{ $school->phone ?? '0784 506 070' }}</div>
    </div>
    <div class="signature-cell signature-right">
        <div class="school-info">
            {{ $school->name ?? 'Don Bosco Secondary School - Didia' }}<br>
            {{ $school->address ?? 'P.O. Box 1234, Didia' }}<br>
            TANZANIA<br>
            {{ $school->website ?? 'www.donbosco-didia.ac.tz' }}
        </div>
    </div>
</div>
        </div>

        <div style="page-break-after: always;"></div>
    @endforeach
</body>
</html>