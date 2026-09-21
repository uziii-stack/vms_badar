@extends('layouts.layout')
@section("content")
<style>
    .gradient-class {
        background: #2BC0E4;
        background: -webkit-linear-gradient(to right, #EAECC6, #2BC0E4);
        background: linear-gradient(to right, #EAECC6, #2BC0E4);
    }

    #myVideo {
        position: fixed;
        min-width: 100%;
        min-height: 100%;
    }

    @media (max-width: 768px) {
        #myVideo {
            display: none;
        }
    }

    .session-options {
        display: grid;
        gap: 10px;
        max-height: 220px;
        overflow-y: auto;
        padding: 4px;
    }

    .session-option {
        border: 1px solid #d7dee8;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.86);
        cursor: pointer;
        display: flex;
        gap: 10px;
        padding: 12px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }

    .session-option:hover,
    .session-option:has(input:checked) {
        border-color: #2BC0E4;
        background: #f2fcff;
        box-shadow: 0 8px 18px rgba(43, 192, 228, 0.18);
    }

    .session-option input {
        margin-top: 4px;
        flex: 0 0 auto;
    }

    .session-title {
        display: block;
        font-weight: 600;
        line-height: 1.25;
    }

    .session-amount {
        display: inline-block;
        margin-top: 6px;
        color: #0f766e;
        font-size: 12px;
        font-weight: 700;
    }
</style>
<div class="page-wrapper gradient-class notPrintable" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"
    data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    @if('newRegistration/IEEEP-2026' === request()->path())
    <video autoplay muted loop id="myVideo">
        <source src="{{asset('videos/ieeep-2026.mp4')}}" type="video/mp4">
        Your browser does not support HTML5 video.
    </video>
    @endif
    <div
        class="position-relative overflow-hidden min-vh-100 d-flex align-items-center justify-content-center">
        <div class="d-flex align-items-center justify-content-center w-100 notPrintable">
            <div class="row justify-content-center w-100 notPrintable">
                <div class="col-md-8 col-lg-6 col-xxl-4 notPrintable">
                    <div class="card mb-0" style="border-radius: 30px;box-shadow: rgb(38, 57, 77) 0px 20px 30px -10px; background:rgba(255, 255, 255, 0.8)">
                        <div class="card-body notPrintable">
                            @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div>{{session('error')}}</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            @elseif(session('message'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <div>{{session('message')}}</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            @endif
                            <a href="#"
                                class="text-nowrap logo-img text-center d-block py-3 w-100">
                                {{-- <img
                                    src="http://ideaspakistan.gov.pk/wp-content/uploads/2024/01/ideas_logo_2024-1.png"
                                    width="180" alt="Ideas Logo"> --}}
                                <img src="{{asset('images/icons/'.$event.'.jpeg') . '?t=' . time() }}" style="width:100%;" alt="{{$event}} Logo">
                            </a>
                            <p class="text-center">Visitor Registration Form</p>
                            <p class="text-center"><b>This registration is only valid for {{$event}}.</b></p>
                            <div class="notPrintable">
                                <form id="visitorForm">
                                    <div id="alertBox"></div>
                                    <div class="modal-body">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Name</label>
                                                    <input type="text" name="name" class="form-control" id="name" maxlength="50"
                                                        pattern="[A-Za-z-]+"
                                                        placeholder="Enter Name" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="nationality" class="form-label">Country</label>
                                                    <select name="nationality" class="form-control" id="nationality">
                                                        <option value="" selected disabled hidden>Select Country</option>
                                                        @foreach (\App\Models\Country::all() as $country)
                                                        <option value="{{ $country->name }}">{{ $country->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="event" value="{{$event}}" />

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="identity" class="form-label">CNIC/Passport</label>
                                                    <input type="text" name="identity"
                                                        class="form-control"
                                                        pattern="^[a-zA-Z0-9-]{9,15}$"
                                                        title="Enter a valid CNIC 13 digit or Passport number"
                                                        id="identity"
                                                        placeholder="42XXXXXXXX1234 or A1234567">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" name="email" class="form-control"
                                                        pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}"
                                                        id="email" placeholder="abc@xyz.com">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="company" class="form-label">Company/Institute Name</label>
                                                    <input type="text" name="company" class="form-control" pattern="[A-Za-z0-9-]+"
                                                        id="company" placeholder="Enter Company Name" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="designation" class="form-label">Designation</label>
                                                    <select name="designation"
                                                        class="form-control"
                                                        id="designation" required>
                                                        <option value="">Select Designation</option>
                                                        <option value="CEO">CEO</option>
                                                        <option value="COO">COO</option>
                                                        <option value="CFO">CFO</option>
                                                        <option value="CTO">CTO</option>
                                                        <option value="CMO">CMO</option>
                                                        <option value="CIO">CIO</option>
                                                        <option value="President">President</option>
                                                        <option value="Vice President">Vice President</option>
                                                        <option value="Executive Director">Executive Director</option>
                                                        <option value="Managing Director">Managing Director</option>
                                                        <option value="Secretary">Secretary</option>
                                                        <option value="Additional Secretary">Additional Secretary</option>
                                                        <option value="Joint Secretary">Joint Secretary</option>
                                                        <option value="Senior Government Officer">Senior Government Officer</option>
                                                        <option value="Founder">Founder</option>
                                                        <option value="General Manager">General Manager</option>
                                                        <option value="Senior Manager">Senior Manager</option>
                                                        <option value="Manager">Manager</option>
                                                        <option value="Assistant Manager">Assistant Manager</option>
                                                        <option value="Team Lead">Team Lead</option>
                                                        <option value="Director">Director</option>
                                                        <option value="Deputy Director">Deputy Director</option>
                                                        <option value="Coordinator">Coordinator</option>
                                                        <option value="Officer">Officer</option>
                                                        <option value="Executive">Executive</option>
                                                        <option value="Student">Student</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="contact" class="form-label">Contact Number</label>
                                                    <input type="number" name="contact" placeholder="923XXYYYYYYY"
                                                        class="form-control" pattern="(\+?\d{1,3})?\d{10}"
                                                        title="Enter a 10-digit contact number with optional country code"
                                                        id="contact" placeholder="Enter Contact Number" maxlength="13">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="sector" class="form-label">Sector</label>
                                                    <select name="sector" class="form-control" id="sector" required>
                                                        <option value="doctor">Doctor</option>
                                                        <option value="other">Other</option>
                                                        <!-- @foreach([
                                                        'Event Management','Research & Development','Defense','Security',
                                                        'Marketing & Advertising','Legal & Consultancy','Non-Profit / NGO',
                                                        'Public Sector','Government','Travel & Hospitality',
                                                        'Media & Entertainment','Textiles & Apparel','E-Commerce',
                                                        'Retail','Food & Beverages','Farming','Agriculture',
                                                        'Power & Utilities','Energy, Oil & Gas','Automotive',
                                                        'Transportation & Logistics','Real Estate & Property','Insurance',
                                                        'Financial Services','Banking','Education & Training',
                                                        'Healthcare & Pharmaceuticals','Engineering & Construction',
                                                        'Manufacturing','Telecommunications','Software Development',
                                                        'Information Technology (IT)','Private LTD','Gaming','Animation'
                                                        ] as $sectorOption)
                                                        <option value="{{ $sectorOption }}">{{ $sectorOption }}</option>
                                                        @endforeach -->
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" id="pmdcWrapper">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="attandeePMDC" class="form-label">PMDC No.</label>
                                                    <input type="text" name="attandeePMDC" class="form-control" id="pmdc" maxlength="50"
                                                        pattern="^[a-zA-Z0-9-]+"
                                                        placeholder="Enter PMDC No.">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="sessions" class="form-label">Topics</label>
                                                    <div class="session-options" id="sessions">
                                                        @forelse($eventSessions as $session)
                                                        <label class="session-option" for="session_{{ $session->id }}">
                                                            <input type="checkbox" name="sessions[]" value="{{ $session->id }}" id="session_{{ $session->id }}">
                                                            <span>
                                                                <span class="session-title">{{ $session->title }}</span>
                                                                @if($session->paid)
                                                                <span class="session-amount">PKR {{ $session->amount }}</span>
                                                                @endif
                                                            </span>
                                                        </label>
                                                        @empty
                                                        <div class="text-muted small">No sessions available</div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                        <br />
                                        <br />
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3 w-50 text-center mx-auto">
                                                    <button type="submit" class="form-control btn btn-primary">
                                                        Submit
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                                <br />
                                <hr />
                                <h3 class="mb-3 text-center mx-auto">Search E-Pass</h3>
                                <p>Note : If you do not receive email, you can search your CNIC here and download your pass again.</p>
                                <br />
                                <form id="cnicSearchForm">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3 text-center mx-auto">
                                                <input type="text" name="searchIdentity" class="form-control" id="searchIdentity" placeholder="Enter CNIC/Passport to Search E-Pass" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 text-center mx-auto">
                                                <button type="submit" class="form-control btn btn-outline-warning">
                                                    Search E-Pass
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- <livewire:self-registration-component :isNew='true' :isInternational="false" :visitorUid='""'
                                eventName="{{$event}}" /> -->
                            <br />
                            @if($event=='PIMEC-2025')
                            <img style="width:445px;width:-webkit-fill-available;"
                                src="{{asset('images/icons/Strip_pimec.png')}}" alt="Partners LOGO" />
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const event = "{{ $event }}";
    const sectorInput = document.getElementById('sector');
    const pmdcWrapper = document.getElementById('pmdcWrapper');
    const pmdcInput = document.getElementById('pmdc');

    function togglePmdcField() {
        const isDoctor = sectorInput.value.toLowerCase() === 'doctor';

        pmdcWrapper.style.display = isDoctor ? '' : 'none';
        pmdcInput.disabled = !isDoctor;

        if (!isDoctor) {
            pmdcInput.value = '';
        }
    }

    sectorInput.addEventListener('change', togglePmdcField);
    togglePmdcField();

    document.getElementById('visitorForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // let badgeWindow = null;

        // badgeWindow = window.open('', '_blank');

        const formData = new FormData(this);

        axios.post("{{ route('request.addVisitor') }}", formData)
            .then(res => {
                document.getElementById('alertBox').innerHTML =
                    `<div class="alert alert-success">Visitor created successfully</div>`;
                if (res.data.data.identity) {
                    window.location.href = `/eventBadge/${event}/${res.data.data.identity}`;
                    // badgeWindow.location.href = `/indusBadge/${res.data.data.identity}`;
                    // window.open(`/indusBadge/${res.data.data.identity}`, "_blank", "width=500,height=500");
                } else {
                    // badgeWindow.close();
                }

                this.reset();
                togglePmdcField();
            })
            .catch(err => {
                let msg = 'Something went wrong';
                // if (badgeWindow) badgeWindow.close();
                console.log(err.data);
                if (err.response?.data?.message) {
                    msg = err.response.data.message;
                }

                document.getElementById('alertBox').innerHTML =
                    `<div class="alert alert-danger">${msg}</div>`;
            });
    });
    document.getElementById('cnicSearchForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // let badgeWindow = null;

        // badgeWindow = window.open('', '_blank');
        const formData = new FormData(this);

        axios.post("{{ route('request.getDataCnic') }}", formData)
            .then(res => {
                document.getElementById('alertBox').innerHTML =
                    `<div class="alert alert-success">Visitor found successfully</div>`;
                if (res.data.data.identity) {
                    window.location.href = `/eventBadge/${event}/${res.data.data.identity}`;
                    // badgeWindow.location.href = `/eventBadge/${event}/${res.data.data.identity}`;
                    // window.open(`/eventBadge/${event}/${res.data.data.identity}`, "_blank", "width=500,height=500");
                } else {
                    // badgeWindow.close();
                }

                this.reset();
            })
            .catch(err => {
                let msg = 'Something went wrong';

                // if (badgeWindow) badgeWindow.close();

                if (err.response?.data?.message) {
                    msg = err.response.data.message;
                }

                document.getElementById('alertBox').innerHTML =
                    `<div class="alert alert-danger">${msg}</div>`;
            });
    });
</script>

@endsection
