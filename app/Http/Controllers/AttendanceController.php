<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Attendance;
use App\Models\Visitors;
use App\Models\DepoGuest;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::active()->get();
        $attendees = collect();
        $event = null;
        $today = now()->toDateString();

        if ($request->event_id) {
            $event = Event::find($request->event_id);

            // If searching
            if ($request->search) {
                $search = $request->search;

                $visitors = Visitors::where(function ($q) use ($search) {
                    $q->where('code', 'LIKE', "%$search%")
                        ->orWhere('email', 'LIKE', "%$search%")
                        ->orWhere('contact', 'LIKE', "%$search%")
                        ->orWhere('identity', 'LIKE', "%$search%");
                })->with([
                    'attendances' => function ($q) use ($request, $today) {
                        $q->where('event_id', $request->event_id)
                            ->where('attendance_date', $today);
                    },
                    'eventSessions' => function ($q) use ($request) {
                        $q->where('event_id', $request->event_id);
                    },
                ])->get();

                $depoGuests = DepoGuest::where(function ($q) use ($search) {
                    $q->where('badge_type', 'LIKE', "%$search%")
                        ->orWhere('depo_guest_email', 'LIKE', "%$search%")
                        ->orWhere('depo_guest_contact', 'LIKE', "%$search%")
                        ->orWhere('depo_identity', 'LIKE', "%$search%");
                })->with(['attendances' => function ($q) use ($request, $today) {
                    $q->where('event_id', $request->event_id)
                        ->where('attendance_date', $today);
                }])->get();

                $attendees = $visitors->merge($depoGuests);
            } else {

                // $visitors = Visitors::with(['attendances' => function ($q) use ($request, $today) {
                //     $q->where('event_id', $request->event_id)
                //         ->where('attendance_date', $today);
                // }])->get();

                // $depoGuests = DepoGuest::with(['attendances' => function ($q) use ($request, $today) {
                //     $q->where('event_id', $request->event_id)
                //         ->where('attendance_date', $today);
                // }])->get();

                // $attendees = $visitors->merge($depoGuests);
            }
        }

        return view('pages.attendance.attendance', compact('events', 'event', 'attendees'));
    }


    /** Mark Attendance (works for any attendee model) */
    public function mark(Request $request)
    {
        $request->validate([
            'attendee_id' => 'required',
            'attendee_type' => 'required',
            'event_id' => 'required',
        ]);

        Attendance::create([
            'attendee_id'   => $request->attendee_id,
            'attendee_type' => $request->attendee_type,
            'event_id'      => $request->event_id,
            'attendance_date' => now()->toDateString(),
            'present' => true,
            'checked_in_at' => now(),
        ]);

        return back()->with('success', '✔ Attendance marked');
    }


    /** Report View */
    public function report(Event $event)
    {
        $attendance = Attendance::with('attendee')
            ->where('event_id', $event->id)
            ->get();

        $attendance->loadMorph('attendee', [
            Visitors::class => ['eventSessions' => function ($q) use ($event) {
                $q->where('event_id', $event->id);
            }],
        ]);

        return view('pages.attendance.report', compact('event', 'attendance'));
    }
}
