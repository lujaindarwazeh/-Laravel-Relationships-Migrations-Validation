<!DOCTYPE html>
<html>
<head>
    <title>Late Students</title>
</head>
<body>
    <h2>Dear Lujain , Here is the List of Students Who Were Late Today</h2>

    @if(count($lateStudents))
        <ul>
            @foreach($lateStudents as $student)
                <li>
                    {{ $student->name }} - {{ $student->email }} - Entered at: {{ $student->enter_date }}
                </li>
            @endforeach
        </ul>
    @else
        <p>No students were late today.</p>
    @endif
</body>
</html>
