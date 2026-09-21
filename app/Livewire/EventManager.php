<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Event;
use App\Models\EventSession;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class EventManager extends Component
{
    use WithFileUploads;

    public $events;
    public $eventSessions;

    public $name;
    public $display_name;
    public $picture;  // for new picture upload
    public $existingPicture; // for showing current picture when editing
    public $sponsor_picture;  // for new sponsor picture upload
    public $existingSponsorPicture; // for showing current sponsor picture when editing
    public $start_date;
    public $end_date;
    public $event_time;
    public $event_location;
    public $website;
    public $policy_content_1;
    public $policy_content_2;
    public $show_cnic_on_badge = true;
    public $show_contact_on_badge = true;
    public $status = 1;

    public $eventIdBeingEdited = null;
    public $sessionIdBeingEdited = null;
    public $sessionTitle = '';
    public $sessionDescription = '';
    public $sessionPaid = false;
    public $sessionAmount = null;

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('events')->ignore($this->eventIdBeingEdited),
            ],
            'display_name' => 'required|string|max:255',
            'picture' => 'nullable|image|max:1024',  // adjust size limit
            'sponsor_picture' => 'nullable|image|max:1024',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'event_time' => 'nullable|string|max:255',
            'event_location' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'policy_content_1' => 'nullable|string',
            'policy_content_2' => 'nullable|string',
            'show_cnic_on_badge' => 'required|boolean',
            'show_contact_on_badge' => 'required|boolean',
            'status' => 'required|integer|in:0,1',
        ];
    }

    public function mount()
    {
        $this->eventSessions = collect();
        $this->loadEvents();
    }

    public function loadEvents()
    {
        $this->events = Event::active()->orderBy('start_date', 'desc')->get();
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->display_name = '';
        $this->picture = null;
        $this->existingPicture = null;
        $this->sponsor_picture = null;
        $this->existingSponsorPicture = null;
        $this->start_date = '';
        $this->end_date = '';
        $this->event_time = '';
        $this->event_location = '';
        $this->website = '';
        $this->policy_content_1 = '';
        $this->policy_content_2 = '';
        $this->show_cnic_on_badge = true;
        $this->show_contact_on_badge = true;
        $this->status = 1;
        $this->eventIdBeingEdited = null;
        $this->resetSessionInputFields();
        $this->eventSessions = collect();
    }

    public function resetSessionInputFields()
    {
        $this->sessionIdBeingEdited = null;
        $this->sessionTitle = '';
        $this->sessionDescription = '';
        $this->sessionPaid = false;
        $this->sessionAmount = null;
    }

    public function loadEventSessions()
    {
        $this->eventSessions = $this->eventIdBeingEdited
            ? EventSession::where('event_id', $this->eventIdBeingEdited)->latest()->get()
            : collect();
    }

    public function createEvent()
    {
        $validated = $this->validate();

        $path = null;
        if ($this->picture) {
            $path = $this->picture->store('events', 'public');
        }

        $sponsorPath = null;
        if ($this->sponsor_picture) {
            $sponsorPath = $this->sponsor_picture->store('sponsors', 'public');
        }

        Event::create([
            'name' => $this->name,
            'display_name' => $this->display_name,
            'picture' => $path,
            'sponsor_picture' => $sponsorPath,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'event_time' => $this->event_time,
            'event_location' => $this->event_location,
            'website' => $this->website,
            'policy_content_1' => $this->policy_content_1,
            'policy_content_2' => $this->policy_content_2,
            'show_cnic_on_badge' => (bool) $this->show_cnic_on_badge,
            'show_contact_on_badge' => (bool) $this->show_contact_on_badge,
            'status' => $this->status,
            'deleted' => 0,
        ]);

        session()->flash('message', 'Event created successfully.');

        $this->resetInputFields();
        $this->loadEvents();
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        if ($event->deleted) {
            session()->flash('error', 'Cannot edit a deleted event.');
            return;
        }

        $this->eventIdBeingEdited = $event->id;
        $this->name = $event->name;
        $this->display_name = $event->display_name;
        $this->existingPicture = $event->picture;
        $this->picture = null;  // reset upload
        $this->existingSponsorPicture = $event->sponsor_picture;
        $this->sponsor_picture = null;  // reset upload
        $this->start_date = $event->start_date;
        $this->end_date = $event->end_date;
        $this->event_time = $event->event_time;
        $this->event_location = $event->event_location;
        $this->website = $event->website;
        $this->policy_content_1 = $event->policy_content_1;
        $this->policy_content_2 = $event->policy_content_2;
        $this->show_cnic_on_badge = (bool) $event->show_cnic_on_badge;
        $this->show_contact_on_badge = (bool) $event->show_contact_on_badge;
        $this->status = $event->status;
        $this->resetSessionInputFields();
        $this->loadEventSessions();
    }

    public function updateEvent()
    {
        $validated = $this->validate();

        $event = Event::findOrFail($this->eventIdBeingEdited);
        if ($event->deleted) {
            session()->flash('error', 'Cannot update a deleted event.');
            return;
        }

        $path = $event->picture;
        if ($this->picture) {
            // Optionally delete old picture
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $path = $this->picture->store('events', 'public');
        }

        $sponsorPath = $event->sponsor_picture;
        if ($this->sponsor_picture) {
            if ($sponsorPath && Storage::disk('public')->exists($sponsorPath)) {
                Storage::disk('public')->delete($sponsorPath);
            }
            $sponsorPath = $this->sponsor_picture->store('sponsors', 'public');
        }

        $event->update([
            'name' => $this->name,
            'display_name' => $this->display_name,
            'picture' => $path,
            'sponsor_picture' => $sponsorPath,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'event_time' => $this->event_time,
            'event_location' => $this->event_location,
            'website' => $this->website,
            'policy_content_1' => $this->policy_content_1,
            'policy_content_2' => $this->policy_content_2,
            'show_cnic_on_badge' => (bool) $this->show_cnic_on_badge,
            'show_contact_on_badge' => (bool) $this->show_contact_on_badge,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Event updated successfully.');

        $this->resetInputFields();
        $this->loadEvents();
    }

    public function softDelete($id)
    {
        $event = Event::findOrFail($id);
        if ($event->deleted) {
            session()->flash('error', 'Event already deleted.');
            return;
        }
        $event->update(['deleted' => 1]);
        session()->flash('message', 'Event deleted (soft).');
        $this->loadEvents();
    }

    public function saveSession()
    {
        if (!$this->eventIdBeingEdited) {
            session()->flash('error', 'Please select an event before adding sessions.');
            return;
        }

        $validated = $this->validate([
            'sessionTitle' => 'required|string|max:255',
            'sessionDescription' => 'nullable|string',
            'sessionPaid' => 'required|boolean',
            'sessionAmount' => 'nullable|numeric|min:0',
        ]);

        EventSession::updateOrCreate(
            [
                'id' => $this->sessionIdBeingEdited,
                'event_id' => $this->eventIdBeingEdited,
            ],
            [
                'event_id' => $this->eventIdBeingEdited,
                'title' => $validated['sessionTitle'],
                'description' => $validated['sessionDescription'],
                'paid' => (bool) $validated['sessionPaid'],
                'amount' => $validated['sessionPaid'] ? $validated['sessionAmount'] : null,
            ]
        );

        session()->flash('message', $this->sessionIdBeingEdited ? 'Session updated successfully.' : 'Session created successfully.');
        $this->resetSessionInputFields();
        $this->loadEventSessions();
    }

    public function editSession($id)
    {
        $session = EventSession::where('event_id', $this->eventIdBeingEdited)->findOrFail($id);

        $this->sessionIdBeingEdited = $session->id;
        $this->sessionTitle = $session->title;
        $this->sessionDescription = $session->description;
        $this->sessionPaid = $session->paid;
        $this->sessionAmount = $session->amount;
    }

    public function deleteSession($id)
    {
        $session = EventSession::where('event_id', $this->eventIdBeingEdited)->findOrFail($id);

        if ($this->sessionIsUsed($session)) {
            session()->flash('error', 'Cannot delete this session because it is already used.');
            return;
        }

        $session->delete();
        session()->flash('message', 'Session deleted successfully.');
        $this->resetSessionInputFields();
        $this->loadEventSessions();
    }

    protected function sessionIsUsed(EventSession $session)
    {
        return $session->visitors()->exists();
    }

    public function render()
    {
        return view('livewire.event-manager');
    }
}
