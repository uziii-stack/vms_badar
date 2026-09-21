<!DOCTYPE html>
<html>

<head>
    <title>{{ $event->name }} Attendance Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">

    <h2>📑 Attendance Report — {{ $event->display_name }}</h2>
    <hr>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Attendee Name</th>
                <th>Type</th>
                <th>Sessions</th>
                <th>Checked In At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendance as $a)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $a->attendee->name ?? $a->attendee->depo_guest_name }}</td>
                <td>{{ class_basename($a->attendee_type) }}</td>
                <td>
                    @if($a->attendee && method_exists($a->attendee, 'eventSessions') && $a->attendee->eventSessions->isNotEmpty())
                        {{ $a->attendee->eventSessions->pluck('title')->join(', ') }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ $a->checked_in_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
