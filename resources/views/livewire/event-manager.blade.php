<div>
    <div class="container mt-4">
        <h2>{{ $eventIdBeingEdited ? 'Edit Event' : 'Create Event' }}</h2>

        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="{{ $eventIdBeingEdited ? 'updateEvent' : 'createEvent' }}" enctype="multipart/form-data">
            @csrf  {{-- not strictly needed since Livewire handles this --}}
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input wire:model.defer="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter unique name">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="display_name" class="form-label">Display Name</label>
                <input wire:model.defer="display_name" type="text" class="form-control @error('display_name') is-invalid @enderror" id="display_name" placeholder="Enter display name">
                @error('display_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="picture" class="form-label">Picture</label>
                <input wire:model="picture" type="file" accept="image/*" class="form-control @error('picture') is-invalid @enderror" id="picture">
                @error('picture') <div class="invalid-feedback">{{ $message }}</div> @enderror

                @if ($existingPicture)
                    <div class="mt-2">
                        <label>Current Picture:</label><br>
                        <img src="{{ asset('storage/' . $existingPicture) }}" alt="Current Picture" style="max-width: 200px;">
                    </div>
                @endif
            </div>

            <div class="mb-3">
                <label for="sponsor_picture" class="form-label">Sponsor Picture</label>
                <input wire:model="sponsor_picture" type="file" accept="image/*" class="form-control @error('sponsor_picture') is-invalid @enderror" id="sponsor_picture">
                @error('sponsor_picture') <div class="invalid-feedback">{{ $message }}</div> @enderror

                @if ($existingSponsorPicture)
                    <div class="mt-2">
                        <label>Current Sponsor Picture:</label><br>
                        <img src="{{ asset('storage/' . $existingSponsorPicture) }}" alt="Current Sponsor Picture" style="max-width: 200px;">
                    </div>
                @endif
            </div>

            <div class="mb-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input wire:model.defer="start_date" type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date">
                @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="end_date" class="form-label">End Date</label>
                <input wire:model.defer="end_date" type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date">
                @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="event_time" class="form-label">Timing</label>
                <input wire:model.defer="event_time" type="text" class="form-control @error('event_time') is-invalid @enderror" id="event_time" placeholder="Example: 10:00 AM - 6:00 PM">
                @error('event_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="event_location" class="form-label">Location</label>
                <input wire:model.defer="event_location" type="text" class="form-control @error('event_location') is-invalid @enderror" id="event_location" placeholder="Enter event location">
                @error('event_location') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="website" class="form-label">Website</label>
                <input wire:model.defer="website" type="text" class="form-control @error('website') is-invalid @enderror" id="website" placeholder="Example: https://example.com">
                @error('website') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3" wire:ignore>
                <label class="form-label">Policy Content Section 1</label>
                <textarea class="summernote form-control @error('policy_content_1') is-invalid @enderror"
                    id="eventPolicySummernote1"
                    name="policy_content_1">{!! $policy_content_1 !!}</textarea>
            </div>
            @error('policy_content_1') <div class="text-danger mt-1">{{ $message }}</div> @enderror

            <div class="mb-3" wire:ignore>
                <label class="form-label">Policy Content Section 2</label>
                <textarea class="summernote form-control @error('policy_content_2') is-invalid @enderror"
                    id="eventPolicySummernote2"
                    name="policy_content_2">{!! $policy_content_2 !!}</textarea>
            </div>
            @error('policy_content_2') <div class="text-danger mt-1">{{ $message }}</div> @enderror

            <div class="mb-3">
                <label class="form-label">Badge Visibility</label>
                <div class="form-check">
                    <input wire:model.defer="show_cnic_on_badge" type="checkbox" class="form-check-input @error('show_cnic_on_badge') is-invalid @enderror" id="show_cnic_on_badge">
                    <label for="show_cnic_on_badge" class="form-check-label">Show CNIC/Passport on badge</label>
                    @error('show_cnic_on_badge') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-check mt-2">
                    <input wire:model.defer="show_contact_on_badge" type="checkbox" class="form-check-input @error('show_contact_on_badge') is-invalid @enderror" id="show_contact_on_badge">
                    <label for="show_contact_on_badge" class="form-check-label">Show Contact/Mobile on badge</label>
                    @error('show_contact_on_badge') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select wire:model.defer="status" id="status" class="form-select @error('status') is-invalid @enderror">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">{{ $eventIdBeingEdited ? 'Update Event' : 'Create Event' }}</button>
                @if($eventIdBeingEdited)
                    <button type="button" class="btn btn-secondary ms-2" wire:click="resetInputFields">Cancel</button>
                @endif
            </div>
        </form>

        @if($eventIdBeingEdited)
            <hr>

            <h3 class="mt-4">Sessions</h3>

            <form wire:submit.prevent="saveSession">
                <div class="mb-3">
                    <label for="sessionTitle" class="form-label">Title</label>
                    <input wire:model.defer="sessionTitle" type="text" class="form-control @error('sessionTitle') is-invalid @enderror" id="sessionTitle" placeholder="Enter session title">
                    @error('sessionTitle') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="sessionDescription" class="form-label">Description</label>
                    <textarea wire:model.defer="sessionDescription" class="form-control @error('sessionDescription') is-invalid @enderror" id="sessionDescription" rows="3" placeholder="Enter session description"></textarea>
                    @error('sessionDescription') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3 form-check">
                    <input wire:model.defer="sessionPaid" type="checkbox" class="form-check-input @error('sessionPaid') is-invalid @enderror" id="sessionPaid">
                    <label for="sessionPaid" class="form-check-label">Paid</label>
                    @error('sessionPaid') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="sessionAmount" class="form-label">Amount</label>
                    <input wire:model.defer="sessionAmount" type="number" step="0.01" min="0" class="form-control @error('sessionAmount') is-invalid @enderror" id="sessionAmount" placeholder="Enter amount">
                    @error('sessionAmount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">{{ $sessionIdBeingEdited ? 'Update Session' : 'Add Session' }}</button>
                    @if($sessionIdBeingEdited)
                        <button type="button" class="btn btn-secondary ms-2" wire:click="resetSessionInputFields">Cancel</button>
                    @endif
                </div>
            </form>

            <table class="table table-bordered table-striped mt-2">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Paid</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($eventSessions as $session)
                        <tr>
                            <td>{{ $session->title }}</td>
                            <td>{{ $session->description ?: '-' }}</td>
                            <td>
                                @if($session->paid)
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </td>
                            <td>{{ $session->amount }}</td>
                            <td>
                                <button type="button" wire:click="editSession({{ $session->id }})" class="btn btn-sm btn-info">Edit</button>
                                <button type="button" wire:click="deleteSession({{ $session->id }})" class="btn btn-sm btn-danger" onclick="confirm('Are you sure you want to delete this session?') || event.stopImmediatePropagation()">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No sessions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endif

        <hr>

        <h2 class="mt-4">Events List</h2>

        <table class="table table-bordered table-striped mt-2">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Display Name</th>
                    <th>Picture</th>
                    <th>Sponsor Picture</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Timing</th>
                    <th>Location</th>
                    <th>Website</th>
                    <th>Badge Fields</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $evt)
                    <tr>
                        <td>{{ $evt->id }}</td>
                        <td>{{ $evt->name }}</td>
                        <td>{{ $evt->display_name }}</td>
                        <td>
                            @if($evt->picture)
                                <img src="{{ asset('storage/' . $evt->picture) }}" alt="Picture" style="max-width: 100px; max-height: 60px;">
                            @else
                                &mdash;
                            @endif
                        </td>
                        <td>
                            @if($evt->sponsor_picture)
                                <img src="{{ asset('storage/' . $evt->sponsor_picture) }}" alt="Sponsor Picture" style="max-width: 100px; max-height: 60px;">
                            @else
                                &mdash;
                            @endif
                        </td>
                        <td>{{ $evt->start_date }}</td>
                        <td>{{ $evt->end_date }}</td>
                        <td>{{ $evt->event_time ?: '-' }}</td>
                        <td>{{ $evt->event_location ?: '-' }}</td>
                        <td>{{ $evt->website ?: '-' }}</td>
                        <td>
                            <span class="badge {{ $evt->show_cnic_on_badge ? 'bg-success' : 'bg-secondary' }}">CNIC</span>
                            <span class="badge {{ $evt->show_contact_on_badge ? 'bg-success' : 'bg-secondary' }}">Contact</span>
                        </td>
                        <td>
                            @if($evt->status == 1)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-badar">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <button wire:click="edit({{ $evt->id }})" class="btn btn-sm btn-info">Edit</button>
                            <button wire:click="softDelete({{ $evt->id }})" class="btn btn-sm btn-danger" onclick="confirm('Are you sure you want to delete this event?') || event.stopImmediatePropagation()">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="text-center">No events found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @script
    <script>
        let eventPolicyEditorsReady = false;

        function initializeEventPolicyEditors() {
            if (!window.jQuery || !$.fn.summernote) {
                setTimeout(initializeEventPolicyEditors, 100);
                return;
            }

            if (!$('#eventPolicySummernote1').next('.note-editor').length) {
                $('#eventPolicySummernote1').summernote({
                    height: 200,
                    callbacks: {
                        onChange: function(contents) {
                            $wire.set('policy_content_1', contents);
                        }
                    }
                });
            }

            if (!$('#eventPolicySummernote2').next('.note-editor').length) {
                $('#eventPolicySummernote2').summernote({
                    height: 200,
                    callbacks: {
                        onChange: function(contents) {
                            $wire.set('policy_content_2', contents);
                        }
                    }
                });
            }

            eventPolicyEditorsReady = true;
            syncEventPolicyEditorsFromLivewire();
        }

        function syncEventPolicyEditorsFromLivewire() {
            if (!eventPolicyEditorsReady) {
                return;
            }

            const policyContent1 = $wire.get('policy_content_1') || '';
            const policyContent2 = $wire.get('policy_content_2') || '';

            if ($('#eventPolicySummernote1').summernote('code') !== policyContent1) {
                $('#eventPolicySummernote1').summernote('code', policyContent1);
            }

            if ($('#eventPolicySummernote2').summernote('code') !== policyContent2) {
                $('#eventPolicySummernote2').summernote('code', policyContent2);
            }
        }

        initializeEventPolicyEditors();

        Livewire.hook('morph.updated', function() {
            initializeEventPolicyEditors();
            syncEventPolicyEditorsFromLivewire();
        });
    </script>
    @endscript
</div>
