<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>O-Level Report Book</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .page-break { page-break-after: always; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: center; }
        th { background: #f9f9f9; }
    </style>
</head>
<body>
    @foreach($results as $row)
        <h2 style="text-align: center; margin-bottom: 5px;">Student Report</h2>
        <p><strong>Name:</strong> {{ $row['student_name'] }}</p>
        <p><strong>Class:</strong> {{ $row['class'] }}</p>
        <p><strong>Semester:</strong> {{ $semester }}</p>

        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Marks</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($row['grades'] as $subjectName => $grade)
                    <tr>
                        <td>{{ $subjectName }}</td>
                        <td>{{ $row['marks'][$subjectName] ?? '-' }}</td>
                        <td>{{ $grade }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p><strong>Total Points (Best 7):</strong> {{ $row['totalPoints'] }}</p>
        <p><strong>Division:</strong> {{ $row['division'] }}</p>
        <p><strong>Rank in Class:</strong> {{ $row['rank'] }}</p>

        <div class="page-break"></div>
    @endforeach
</body>
</html>
