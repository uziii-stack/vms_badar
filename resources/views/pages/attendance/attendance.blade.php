@auth
@extends('layouts.layout')
@section("content")

<div>
    <h2 class="mb-3">📋 Attendance Management</h2>

    {{-- EVENT SELECT --}}
    <form method="GET" action="{{ route('pages.attendance.attendance') }}" class="mb-4">
        <select name="event_id" class="form-control w-50 d-inline" required>
            <option value="">-- Select Event --</option>
            @foreach($events as $ev)
            <option value="{{ $ev->id }}" @if(request('event_id')==$ev->id) selected @endif>
                {{ $ev->display_name }}
            </option>
            @endforeach
        </select>
        <button class="btn btn-primary">Load</button>
    </form>


    @if($event)
    <a href="{{ route('pages.attendance.report',$event->id) }}" class="btn btn-dark mt-3">📑 View Attendance Report</a>

    <h4>Event: <strong>{{ $event->display_name }}</strong></h4>

    <form method="GET" action="{{ route('pages.attendance.attendance') }}" class="row mb-3 g-2">

        <input type="hidden" name="event_id" value="{{ $event->id }}">

        <div class="col-md-6">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                placeholder="Search by code / badge / email / contact / identity">
        </div>

        <div class="col-md-2">
            <button class="btn btn-dark w-100">Search</button>
        </div>

    </form>

    @if($attendees->count() == 0)
    <div class="alert alert-warning">No attendee matched your search!</div>
    @else

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Code / Badge</th>
                <th>Email / Contact</th>
                <th>Event</th>
                <th>Sessions</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendees as $a)
            <tr>
                <td>{{ $a->name ?? $a->depo_guest_name }}</td>

                <td>{{ $a->code ?? $a->badge_type }}</td>

                <td>{{ $a->email ?? $a->depo_guest_email ?? $a->depo_guest_contact }}</td>
                <td>{{ $a->event ?? ''}}</td>
                <td>
                    @if(method_exists($a, 'eventSessions') && $a->eventSessions->isNotEmpty())
                        {{ $a->eventSessions->pluck('title')->join(', ') }}
                    @else
                        -
                    @endif
                </td>

                <td>
                    @php
                    $alreadyPresent = $a->attendances->isNotEmpty();
                    @endphp

                    @if($alreadyPresent)

                    <span class="badge bg-success">Present Today</span>
                    <br>
                    <small>{{ $a->attendances[0]->checked_in_at }}</small>

                    @else

                    <form method="POST" action="{{ route('request.attendance.mark') }}">
                        @csrf

                        <input type="hidden" name="attendee_id" value="{{ $a->id ?? $a->uid }}">
                        <input type="hidden" name="attendee_type"
                            value="{{ $a instanceof \App\Models\Visitors ? 'App\Models\Visitors' : 'App\Models\DepoGuest' }}">
                        <input type="hidden" name="event_id" value="{{ $event->id }}">

                        <button class="btn btn-success btn-sm">Mark Present</button>
                    </form>

                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @endif


    @endif
</div>
@endsection
@endauth
