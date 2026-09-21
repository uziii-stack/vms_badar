<div class="table-responsive">
    <table class="table text-nowrap mb-0 align-middle">
        <thead class="text-dark fs-4">
            <tr>
                <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <h6 class="fw-semibold mb-0">Name</h6>
                </th>
                <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <h6 class="fw-semibold mb-0">Company/Institute Name</h6>
                </th>
                <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <h6 class="fw-semibold mb-0">Country Name</h6>
                </th>
                <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <h6 class="fw-semibold mb-0">Designation</h6>
                </th>
                <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <h6 class="fw-semibold mb-0">Identity</h6>
                </th>
                <!-- <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <h6 class="fw-semibold mb-0">DOB</h6>
                </th> -->
                <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <h6 class="fw-semibold mb-0">Code</h6>
                </th>
                <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <h6 class="fw-semibold mb-0">Attandee PMDC</h6>
                </th>
                <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <h6 class="fw-semibold mb-0">Stalls Day 1</h6>
                </th>
                <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <h6 class="fw-semibold mb-0">Stalls Day 2</h6>
                </th>
                <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <h6 class="fw-semibold mb-0">Sessions</h6>
                </th>
                @if(session()->get('user')->roles[0]->name =="attandeeUser" ||session()->get('user')->roles[0]->name
                =="admin" ||session()->get('user')->roles[0]->name =="bxssUser" ||
                session()->get('user')->roles[0]->name =="depo")
                <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <h6 class="fw-semibold mb-0">1st Day</h6>
                </th>
                <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <h6 class="fw-semibold mb-0">2nd Day</h6>
                </th>
                <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <h6 class="fw-semibold mb-0">3rd Day</h6>
                </th>
                <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <h6 class="fw-semibold mb-0">4th Day</h6>
                </th>
                <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <h6 class="fw-semibold mb-0">Seminar</h6>
                </th>
                @endif
                @if(session()->get('user')->roles[0]->name =="admin" ||session()->get('user')->roles[0]->name
                =="bxssUser" || session()->get('user')->roles[0]->name =="depo"|| session()->get('user')->roles[0]->name
                =="batchUser"|| session()->get('user')->roles[0]->name=="ncc")
                <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <h6 class="fw-semibold mb-0">Edit</h6>
                </th>
                <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <h6 class="fw-semibold mb-0">Slip</h6>
                </th>
                <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <h6 class="fw-semibold mb-0">Print A4 Badge</h6>
                </th>
                <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <h6 class="fw-semibold mb-0">Badge Print</h6>
                </th>
                <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <h6 class="fw-semibold mb-0">Dupe Badge Print</h6>
                </th>
                @endif
            </tr>
        </thead>
        <tbody>
            @if(!empty($attandees))
            <tr>
                <td class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <h6 class="fw-semibold mb-1 text-capitalize">{{$attandees['name']}}</h6>
                </td>
                <td class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <p class="mb-0 fw-normal mx-auto ">{{$attandees['company']}}</p>
                </td>
                <td class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <p class="mb-0 fw-normal mx-auto ">{{$attandees['nationality']}}</p>
                </td>
                <td class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <p class="mb-0 fw-normal mx-auto ">{{$attandees['designation']}}</p>
                </td>
                <td class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <p class="mb-0 fw-normal mx-auto ">{{$attandees['identity']}}</p>
                </td>
                <!-- <td class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <p class="mb-0 fw-normal mx-auto ">$attandees['dob']</p>
                </td> -->
                <td class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <p class="mb-0 fw-normal mx-auto ">{{$attandees['code']}}</p>
                </td>
                <td class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <p class="mb-0 fw-normal mx-auto ">{{$attandees['attandeePMDC']}}</p>
                </td>
                <td class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <button class="btn btn-outline-primary" wire:click="$dispatch('open-stall-modal', {day:'day_1'})" style="font-size: 24px;"><i class="ti ti-circle-check "></i></button>
                </td>
                <td class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <button class="btn btn-outline-warning" wire:click="$dispatch('open-stall-modal', {day:'day_2'})" style="font-size: 24px;"><i class="ti ti-circle-check "></i></button>
                </td>
                <td class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    @php
                    $eventSessions = collect(data_get($attandees, 'event_sessions', data_get($attandees, 'eventSessions', [])));
                    @endphp
                    @forelse($eventSessions as $session)
                        <p class="mb-1 fw-normal mx-auto">{{ $session['title'] ?? $session->title }}</p>
                    @empty
                        <p class="mb-0 fw-normal mx-auto">-</p>
                    @endforelse
                </td>
                @if(session()->get('user')->roles[0]->name =="attandeeUser" ||session()->get('user')->roles[0]->name
                =="admin" ||session()->get('user')->roles[0]->name =="bxssUser" ||
                session()->get('user')->roles[0]->name =="depo")
                <td class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <livewire:mark-attendance-component key="day_1" day="day_1" :uid="$attandees['uid']" />
                </td>
                <td class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <livewire:mark-attendance-component key="day_2" day="day_2" :uid="$attandees['uid']" />
                </td>
                <td class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <livewire:mark-attendance-component key="day_3" day="day_3" :uid="$attandees['uid']" />
                </td>
                <td class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <livewire:mark-attendance-component key="day_4" day="day_4" :uid="$attandees['uid']" />
                </td>
                <td class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <livewire:mark-attendance-component key="seminar" day="seminar" :uid="$attandees['uid']" />
                </td>
                @endif
                @if(session()->get('user')->roles[0]->name =="admin" ||session()->get('user')->roles[0]->name
                =="bxssUser" || session()->get('user')->roles[0]->name =="depo"|| session()->get('user')->roles[0]->name
                =="batchUser"|| session()->get('user')->roles[0]->name=="ncc")
                <td class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <livewire:add-attandee-component :isNew='false' :visitorUid="$attandees['identity']" />
                </td>
                <td class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <button class="btn btn-outline-primary" wire:click.prevent="redirectToSlip" style="font-size: 24px;"
                        href="" target="_blank"><i class="ti ti-clipboard-data"></i></button>
                </td>
                <td class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    <button type="button" class="btn btn-outline-primary"
                        onclick="openSingleA4BadgeOptions(@js($attandees['identity']))"
                        style="font-size: 24px;"><i class="ti ti-id-badge-2"></i></button>
                </td>
                <td class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    @if($attandees['badge_print'])
                    <span class="badge mx-auto  bg-success rounded-3 fw-semibold">Printed</span>
                    @else
                    <button class="btn btn-outline-primary" wire:click.prevent="redirectToBadge"
                        style="font-size: 24px;" href="" target="_blank"><i class="ti ti-id-badge-2"></i></button>
                    @endif
                </td>
                <td class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                    @if($attandees['badge_print'])
                    <a class="btn btn-outline-warning" wire:click.prevent="redirectToBadge" style="font-size: 24px;"
                        href="" target="_blank"><i
                            class="ti ti-number-{{($attandees['dupe_badge_print']>9)?'9+':$attandees['dupe_badge_print']}}"></i></a>
                    @else
                    <span class="badge mx-auto  bg-warning rounded-3 fw-semibold">Badge Not Printed</span>
                    @endif
                </td>
                @endif
            </tr>
            @else
            <tr>
                <td colspan="12">No Data Available</td>
            </tr>
            @endif
        </tbody>
    </table>
    <div wire:ignore.self class="modal fade" id="singleA4BadgeOptionsModal" tabindex="-1" aria-labelledby="singleA4BadgeOptionsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="singleA4BadgeOptionsModalLabel">Print A4 Badge</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="singleA4BadgeEvent" class="form-label">Event</label>
                        <select id="singleA4BadgeEvent" class="form-select">
                            <option value="">Select event</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}">{{ $event->display_name ?: $event->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="singleA4BadgeVisitorType" class="form-label">Visitor Type</label>
                        <select id="singleA4BadgeVisitorType" class="form-select">
                            <option value="">Select visitor type</option>
                            <option value="0">Trade Visitor</option>
                            <option value="1">Exhibitor</option>
                            <option value="2">Event Manager</option>
                            <option value="3">Organizer</option>
                            <option value="4">Guest Of Honour</option>
                            <option value="5">Panelist</option>
                            <option value="6">Volunteer</option>
                        </select>
                    </div>
                    <div class="alert alert-danger d-none" id="singleA4BadgeOptionsError"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="openSingleA4BadgePrint">Open Badge</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stall Selection Modal -->
    <div wire:ignore.self class="modal fade" id="stallModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Select Stalls</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div wire:loading class="text-center p-3">
                        Loading stalls...
                    </div>
                    <div class="row" wire:loading.remove>
                        @foreach($stallList as $stall)
                        <div class="col-md-4 mb-2">
                            <div class="form-check" wire:key="stall-{{ $stall['id'] }}">
                                <input class="form-check-input"
                                    type="checkbox"
                                    wire:model="selectedStalls"
                                    value="{{ $stall['id'] }}"
                                    id="stall_{{ $stall['id'] }}">

                                <label class="form-check-label" for="stall_{{ $stall['id'] }}">
                                    {{ $stall['name'] }}
                                </label>
                            </div>
                        </div>
                        @endforeach


                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button class="btn btn-primary" wire:click="saveStalls">
                        Save
                    </button>
                </div>

            </div>
        </div>
    </div>

    @if(!empty($attandees))
    @script
    <script>
        $wire.on('redirectNow', (data) => {
            window.open(`{{url('')}}/badge/badge/${data[0]['identity']}`, "_blank", "width=500,height=500");
        });
        $wire.on('redirectToSlip', (data) => {
            window.open(`{{url('')}}/slip/${data[0]['identity']}`, "_blank", "width=500,height=500");
        });
    </script>
    @endscript
    @script
    <script>
        window.singleA4BadgeIdentity = '';
        window.singleA4BadgeOptionsModal = null;

        function getSingleA4BadgeOptionsModal() {
            const modalElement = document.getElementById('singleA4BadgeOptionsModal');

            if (!window.singleA4BadgeOptionsModal && modalElement) {
                window.singleA4BadgeOptionsModal = new bootstrap.Modal(modalElement);
            }

            return window.singleA4BadgeOptionsModal;
        }

        function showSingleA4BadgeOptionsError(message) {
            $('#singleA4BadgeOptionsError').removeClass('d-none').text(message);
        }

        window.openSingleA4BadgeOptions = function(identity) {
            window.singleA4BadgeIdentity = identity || '';
            $('#singleA4BadgeOptionsError').addClass('d-none').text('');
            $('#singleA4BadgeEvent').val('');
            $('#singleA4BadgeVisitorType').val('');
            getSingleA4BadgeOptionsModal()?.show();
        };

        $('#openSingleA4BadgePrint').on('click', function() {
            const eventId = $('#singleA4BadgeEvent').val();
            const visitorType = $('#singleA4BadgeVisitorType').val();

            if (!eventId) {
                showSingleA4BadgeOptionsError('Please select an event.');
                return;
            }

            if (visitorType === '') {
                showSingleA4BadgeOptionsError('Please select a visitor type.');
                return;
            }

            if (!window.singleA4BadgeIdentity) {
                showSingleA4BadgeOptionsError('Visitor identity is missing.');
                return;
            }

            getSingleA4BadgeOptionsModal()?.hide();
            window.open(`{{url('')}}/a4EBadge/${encodeURIComponent(eventId)}/${encodeURIComponent(visitorType)}/${encodeURIComponent(window.singleA4BadgeIdentity)}`, "_blank", "width=500,height=500");
        });

        const stallModal = new bootstrap.Modal(
            document.getElementById('stallModal')
        );

        Livewire.on('open-stall-modal', () => {
            stallModal.show();
        });

        Livewire.on('close-stall-modal', () => {
            stallModal.hide();
        });
    </script>
    @endscript

    @endif
</div>
