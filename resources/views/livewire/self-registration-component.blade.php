<div class="notPrintable">
    @if($message)
    <div class="alert alert-{{ $messageType }} mt-3 alert-dismissible fade show" role="alert" id="autoFadeAlert">
        {{ $message }}
        @if($anchor)
        <a href="{{$anchor}}" onclick="window.open(this.href, 'popupWindow', 'width=800,height=600,scrollbars=yes,resizable=yes'); return false;">Print Badge</a>
        @endif
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" wire:model='name' class="form-control @error('name') is-invalid @enderror"
                        id="name" placeholder="Enter Name" required>
                    <div class="invalid-feedback">
                        @error('name')<span class="error">
                            {{ $message }}
                        </span>@enderror
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nationality" class="form-label">Country</label>
                    <select wire:model='nationality' class="form-control @error('nationality') is-invalid @enderror"
                        id="nationality">
                        <option value="" selected disabled hidden> Select Country</option>
                        @foreach (\App\Models\Country::all() as $key=>$country)
                        <option value="{{$country->name}}">{{$country->name}}</option>
                        {{-- if($isInternational && $country->name != 'Pakistan')
                        elseif(!$isInternational)
                        <option value="{{$country->name}}">{{$country->name}}</option>
                        endif --}}
                        @endforeach
                    </select>
                    <div class="invalid-feedback">
                        @error('nationality')<span class="error">
                            {{ $message }}
                        </span>@enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                {{-- if(!$isInternational) --}}
                <div class="mb-3">
                    <label for="identity" class="form-label">CNIC/Passport</label>
                    <input type="text" wire:model='identity'
                        class="form-control @error('identity') is-invalid @enderror" pattern="^[a-zA-Z0-9]{9,13}$"
                        id="identity" placeholder="Enter Identity">
                    <div class="invalid-feedback">
                        @error('identity')<span class="error">
                            {{ $message }}
                        </span>@enderror
                    </div>
                </div>
                {{-- else
                <div class="mb-3">
                    <label for="identity" class="form-label">Passport</label>
                    <input type="text" wire:model='identity'
                        class="form-control @error('identity') is-invalid @enderror" pattern="^[a-zA-Z0-9]{6,9}$"
                        id="identity" placeholder="Enter Passport">
                    <div class="invalid-feedback">
                        @error('identity')<span class="error">
                            {{ $message }}
                </span>@enderror
            </div>
        </div>
        @ndif --}}
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="text" wire:model='email' class="form-control @error('email') is-invalid @enderror"
                id="email" placeholder="Enter Email Address">
            <div class="invalid-feedback">
                @error('email')<span class="error">
                    {{ $message }}
                </span>@enderror
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="company" class="form-label">Company/Institute Name</label>
            <input type="text" wire:model='company' class="form-control @error('company') is-invalid @enderror"
                id="company" placeholder="Enter Company Name" required>
            <div class="invalid-feedback">
                @error('company')<span class="error">
                    {{ $message }}
                </span>@enderror
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="designation" class="form-label">Designation</label>
            <select wire:model.live="designation"
                class="form-control @error('designation') is-invalid @enderror" id="designation" required>
                <option value="">Select Designation</option>

                {{-- Common Corporate Designations --}}
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

            <div class="invalid-feedback">
                @error('designation')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>

            @if($designation === 'Other')
            <input type="text"
                wire:model="otherDesignation"
                class="form-control mt-2 @error('otherDesignation') is-invalid @enderror"
                placeholder="Enter your designation">

            <div class="invalid-feedback">
                @error('otherDesignation') <span class="error">{{ $message }}</span> @enderror
            </div>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="contact" class="form-label">Contact Number</label>
            <input type="text" wire:model="contact" class="form-control @error('contact') is-invalid @enderror"
                id="contact" placeholder="Enter Contact Number">
            <div class="invalid-feedback">
                @error('contact')<span class="error">
                    {{ $message }}
                </span>@enderror
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="sector" class="form-label">Sector</label>
            <select wire:model.live="sector"
                class="form-control @error('sector') is-invalid @enderror"
                id="sector" required>
                <option value="">Select Sector</option>

                {{-- Sector Options --}}
                @php
                $sectors = [
                'Event Management', 'Research & Development', 'Defense', 'Security', 'Marketing & Advertising',
                'Legal & Consultancy', 'Non-Profit / NGO', 'Public Sector', 'Government', 'Travel & Hospitality',
                'Media & Entertainment', 'Textiles & Apparel', 'E-Commerce', 'Retail', 'Food & Beverages',
                'Farming', 'Agriculture', 'Power & Utilities', 'Energy, Oil & Gas', 'Automotive',
                'Transportation & Logistics', 'Real Estate & Property', 'Insurance', 'Financial Services',
                'Banking', 'Education & Training', 'Healthcare & Pharmaceuticals', 'Engineering & Construction',
                'Manufacturing', 'Telecommunications', 'Software Development', 'Information Technology (IT)',
                'Private LTD', 'Gaming', 'Animation'
                ];
                @endphp

                @foreach($sectors as $sectorOption)
                <option value="{{ $sectorOption }}">{{ $sectorOption }}</option>
                @endforeach
            </select>

            <div class="invalid-feedback">
                @error('sector') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="mb-3 w-50 text-center mx-auto">
            <!-- <label for="attandeeEmail" class="form-label">Save & Print</label> -->
            <button wire:click="addNew" class="form-control btn btn-primary">Submit</button>
            @if(session()->has('message'))
            <div class="alert alert-success mt-3">
                {{ session('message') }}
            </div>
            @endif
            @if(session()->has('error'))
            <div class="alert alert-danger mt-3">
                {{ session('error') }}
            </div>
            @endif
        </div>
    </div>
</div>
<br />
<br />
<hr />
<h4 class="text-center mx-auto">Search CNIC for E-Pass</h4>
<br />
<br />
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="searchIdentity" class="form-label">Search CNIC for E-Pass </label>
            <input type="text" wire:model="searchIdentity" class="form-control @error('searchIdentity') is-invalid @enderror"
                id="searchIdentity" placeholder="Enter CNIC Number">
            <div class="invalid-feedback">
                @error('searchIdentity')<span class="error">
                    {{ $message }}
                </span>@enderror
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="searchVisitor" class="form-label">Search</label>
            <button wire:click="searchVisitor" class="form-control btn btn-outline-warning">Search</button>
            @if(session()->has('message'))
            <div class="alert alert-warning mt-3">
                {{ session('message') }}
            </div>
            @endif
            @if(session()->has('searchError'))
            <div class="alert alert-danger mt-3">
                {{ session('searchError') }}
            </div>
            @endif
        </div>
    </div>
</div>
<!-- if(!$isInternational)
script
    <script>
        $wire.on('slipPrint', (data) => {
            window.open(`{{url('')}}/slip/${data}`, "_blank", "width=500,height=500");
        });
    </script>
    endscript
    else -->
@script
<script>
    $wire.on('slipPrint', (data) => {
        window.open(`{{url('')}}/indusBadge/${data}`, "_blank", "width=500,height=500");
    });
</script>
@endscript
</div>