<!DOCTYPE html>
<html>
<head>
    <title>Advanced Level Results Report</title>
    <style>
        body { font-family: sans-serif; margin: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: center; font-size: 11px; }
        th { background-color: #f2f2f2; }
        h1, h2 { text-align: center; }
        .row-rank-1 { background-color: #fffbeb; font-weight: bold; }
        .row-rank-2 { background-color: #f9fafb; font-weight: bold; }
    </style>
</head>
<body>

    <h2>Advanced Level Results Report - {{ $semester }}</h2>
    <p style="text-align:center;">
        <strong>Class:</strong> {{ $class }} | 
        <strong>Combination:</strong> {{ $combination }} | 
        <strong>Included Marks:</strong> {{ implode(', ', array_map('ucfirst', $components)) }} <br>
        <strong>Generated:</strong> {{ now()->format('M d, Y') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Class</th>
                <th>Rank</th>
                <th>Student</th>
                @foreach($subjects as $subjectName)
                    <th colspan="2">{{ $subjectName }}</th>
                @endforeach
                <th>Total Points</th>
                <th>Division</th>
            </tr>
            <tr>
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
                <tr class="row-rank-{{ $row['rank'] }}">
                    <td>{{ $row['class'] }}</td>
                    <td>{{ $row['rank'] }}</td>
                    <td>{{ $row['student_name'] }}</td>
                    @foreach($subjects as $subjectName)
                        <td>{{ $row['marks'][$subjectName] ?? '-' }}</td>
                        <td>{{ $row['grades'][$subjectName] ?? '-' }}</td>
                    @endforeach
                    <td>{{ $row['totalPoints'] }}</td>
                    <td>{{ $row['division'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
