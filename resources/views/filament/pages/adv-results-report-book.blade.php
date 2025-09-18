<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Advance Students Report Card</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 10px;
            font-size: 12px;
        }

        .report-card {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 2px solid #2E8B57;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            page-break-inside: avoid;
        }

        /* ==== HEADER TABLE ==== */
        .header-table {
            display: table;
            width: 100%;
            table-layout: fixed;
            background: linear-gradient(135deg, #4CAF50, #2E8B57);
            color: white;
            padding: 8px 0;
            box-sizing: border-box;
        }

        .header-cell {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            padding: 0 5px;
        }

        .photo-cell {
            width: 60px;
            padding-left: 15px;
        }

        .name-cell {
            width: auto;
            padding: 0 10px;
        }

        .logo-cell {
            width: 60px;
            padding-right: 15px;
        }

        .school-name {
            color: black;
            font-size: 16px;
            font-weight: bold;
            line-height: 1.2;
            text-align: center;
            word-wrap: break-word;
        }

        @media print {
            .school-name {
                font-size: 14px;
            }
        }

        .school-name.long-name {
            font-size: 13px !important;
        }

        /* ==== PHOTO & LOGO ==== */
        .student-photo {
            width: 60px;
            height: 60px;
            margin: 0 auto;
            overflow: hidden;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f5f5;
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
        }

        /* ==== SEMESTER BANNER ==== */
        .semester-info {
            background: #2196F3;
            color: white;
            text-transform: uppercase;
            padding: 5px 10px;
            margin: 8px 0 5px 0;
            text-align: center;
            font-weight: bold;
            font-size: 15px;
        }

        /* ==== STUDENT INFO ==== */
        .student-info {
            display: flex;
            padding: 8px;
            background: #e3f2fd;
            align-items: center;
        }

        .student-details {
            flex: 1;
        }

        .student-name {
            background: #ffeb3b;
            padding: 6px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            text-align: center;
        }

        /* ==== MARKS TABLE ==== */
        .marks-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
        }

        .marks-table th,
        .marks-table td {
            border: 1px solid #333;
            padding: 4px;
            text-align: center;
            font-size: 10px;
            line-height: 1.2;
        }

        .marks-table th {
            background: #4CAF50;
            color: white;
            font-weight: bold;
        }

        .subject-column { 
            background: #e3f2fd; 
            font-weight: bold; 
            text-align: left !important; 
            padding-left: 6px;
        }
        .ca-score { background: #fff3e0; }
        .mid-score { background: #f3e5f5; }
        .terminal-score { background: #e8f5e8; }
        .teacher-remarks { 
            text-align: left !important; 
            font-size: 9px; 
            padding: 2px 4px;
            max-width: 80px;
            word-wrap: break-word;
        }
        .total-score { background: #ffeb3b; font-weight: bold; }
        .grade-column { background: #e1f5fe; font-weight: bold; }

        .grade-A { color: #4CAF50; font-weight: bold; }
        .grade-B { color: #2196F3; font-weight: bold; }
        .grade-C { color: #FF9800; font-weight: bold; }
        .grade-D { color: #f44336; font-weight: bold; }
        .grade-F { color: #d32f2f; font-weight: bold; }

        /* ==== ASSESSMENT TITLE ==== */
        .assessment-title {
            background: #4CAF50;
            color: white;
            padding: 6px 10px;
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            margin: 10px 0 8px 0;
            letter-spacing: 0.5px;
        }

        /* ==== ASSESSMENT GRID ==== */
        .assessment-grid {
            display: table;
            width: 100%;
            table-layout: fixed;
            margin: 0 8px;
            border: 1px solid #333;
            background: #f9f9f9;
            border-collapse: collapse;
        }

        .assessment-grid-row {
            display: table-row;
        }

        .assessment-grid-cell {
            display: table-cell;
            padding: 6px 4px;
            text-align: center;
            border-right: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
            vertical-align: middle;
        }

        .assessment-grid-cell:nth-child(2n) {
            border-right: none;
        }

        .assessment-grid .label {
            font-weight: bold;
            color: #2E8B57;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .assessment-grid .value {
            font-weight: bold;
            font-size: 13px;
            color: #000;
            line-height: 1.2;
        }

        /* ==== REMARKS ==== */
        .remarks-section {
            margin: 12px 8px;
            font-size: 11px;
            border-top: 2px solid #4CAF50;
            padding-top: 8px;
        }

        .remarks-title {
            font-weight: bold;
            font-size: 11px;
            color: #2E8B57;
            margin: 8px 0 4px 0;
            padding-left: 5px;
            border-left: 3px solid #2E8B57;
        }

        .remarks-text {
            font-style: italic;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            background: #fff8e1;
            padding: 6px 8px;
            border-radius: 3px;
            margin-bottom: 8px;
            border-left: 2px solid #ffc107;
        }

        /* ==== SIGNATURES ==== */
        .signatures-container {
            margin: 15px 8px 10px 8px;
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .signature-cell {
            display: table-cell;
            vertical-align: top;
            width: 50%;
            padding: 0 5px;
        }

        .signature-left {
            text-align: left;
        }

        .signature-right {
            text-align: right;
        }

        .signature-line-box {
            width: 100%;
            height: 40px;
            border: 1px solid #333;
            margin: 5px 0;
            background: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #666;
        }

        .signature-label {
            font-weight: bold;
            font-size: 10px;
            display: block;
            text-align: center;
            margin-top: 3px;
        }

        .contact-info {
            font-size: 9px;
            color: #666;
            margin-top: 5px;
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
            }
        }

        @page {
            margin: 0.5in;
            size: A4;
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
                STUDENT'S {{ $row['class'] ?? '___' }} SEMESTER REPORT
            </div>

            <!-- Student Information -->
            <div class="student-info">
                <div class="student-details">
                    <div class="student-name">{{ $row['student_name'] ?? '-' }}</div>
                    <div style="text-align: center; font-size: 11px; color: #555;">
                        <strong>Combination:</strong> {{ $combination ?? '-' }}
                    </div>
                </div>
            </div>

            <!-- Marks Table -->
            <table class="marks-table">
                <thead>
                    <tr>
                        <th rowspan="2">SUBJECT</th>
                        <!-- Dynamically generate component columns -->
                        @php
                            // Get components from first subject if available
                            $sampleSubject = !empty($subjects) ? $subjects[0] : null;
                            $components = [];
                            if ($sampleSubject && isset($row['marks'][$sampleSubject])) {
                                $sampleMarks = $row['marks'][$sampleSubject];
                                if (isset($sampleMarks['components'])) {
                                    $components = array_keys($sampleMarks['components']);
                                }
                            }
                        @endphp
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
                    @foreach($subjects as $subjectName)
                        @php
                            $grade = $row['grades'][$subjectName] ?? 'F';
                            $marksData = $row['marks'][$subjectName] ?? [];
                            $componentsData = $marksData['components'] ?? [];
                            $total = $marksData['total'] ?? '-';
                            $out_of = $marksData['out_of'] ?? '-';
                            $average = $marksData['average'] ?? '-';

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
                                <td class="ca-score">{{ $componentsData[$component] ?? '-' }}</td>
                            @endforeach
                            <td class="teacher-remarks">{{ $fullRemark }}</td>
                            <td class="total-score">{{ $total }}</td>
                            <td>{{ $out_of }}</td>
                            <td>{{ $average }}</td>
                            <td class="grade-{{ strtoupper($grade) }} grade-column">{{ $grade }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- ASSESSMENT -->
            <div class="assessment-title">
                STUDENT OVERALL ACADEMIC PERFORMANCE
            </div>

            <div class="assessment-grid">
                <div class="assessment-grid-row">
                    <div class="assessment-grid-cell">
                        <div class="label">WASTANI</div>
                        <div class="value">{{ $row['overallAverage'] ?? '-' }}</div>
                    </div>
                    <div class="assessment-grid-cell">
                        <div class="label">GRADE</div>
                        <div class="value">
                            @php
                                $average = $row['overallAverage'] ?? 0;
                                if ($average >= 75) {
                                    $overallGrade = 'A';
                                } elseif ($average >= 65) {
                                    $overallGrade = 'B';
                                } elseif ($average >= 50) {
                                    $overallGrade = 'C';
                                } elseif ($average >= 30) {
                                    $overallGrade = 'D';
                                } else {
                                    $overallGrade = 'F';
                                }
                            @endphp
                            {{ $overallGrade }}
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
                        <div class="value">{{ $row['totalPoints'] ?? '-' }}</div>
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
            <div class="remarks-section">
                <div class="remarks-title">Class Teacher's Remarks</div>
                <div class="remarks-text">{{ $row['class_teacher_remark'] ?? '-' }}</div>

                <div class="remarks-title">Academic Master's Remarks</div>
                <div class="remarks-text">{{ $row['academic_master_remark'] ?? '-' }}</div>

                <div class="remarks-title">Headmaster's Remarks</div>
                <div class="remarks-text">{{ $row['headmaster_remark'] ?? '-' }}</div>
            </div>

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