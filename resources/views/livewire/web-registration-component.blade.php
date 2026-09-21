<div class="container mx-auto my-5">
    <div class="card">
        <div class="card-body">
            @if (session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form wire:submit.prevent="{{ $step === 3 ? 'submit' : 'next' }}">
                {{-- Step 1 --}}
                @if ($step === 1)
                <h5>Personal Info</h5>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Nationality</label>
                        <select class="form-select" wire:model="nationality" required>
                            <option value="" selected>Select Nationality</option>
                            @foreach (\App\Models\Country::all() as $key=>$country)
                            <option value="{{$country->name}}">{{$country->name}}</option>
                            @endforeach
                        </select>
                        @error('nationality') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label">CNIC/Passport</label>
                        <input type="text" class="form-control" wire:model="identity"
                            placeholder="XXXXX-XXXXXXX-X or Passport Number">
                        @error('identity') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Date of Birth</label>
                        @php
                        $maxDob = now()->subYears(18)->format('Y-m-d');
                        @endphp
                        <input type="date" class="form-control" wire:model="dob" min="1900-01-01" max="{{ $maxDob }}"
                            required>
                        @error('dob') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label">Gender</label>
                        <select class="form-select" wire:model="gender">
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                        @error('gender') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                @endif

                {{-- Step 2 --}}
                @if ($step === 2)
                <h5>Step 2: Contact Info</h5>

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" wire:model="name" required>
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">City</label>
                    <input type="text" class="form-control" wire:model="city" required>
                    @error('city') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Designation</label>
                    <input type="text" class="form-control" wire:model="designation" required>
                    @error('designation') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" class="form-control" wire:model="address" required>
                    @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Mobile</label>
                    <input type="text" class="form-control" wire:model="mobile" placeholder="923001234567" required>
                    @error('mobile') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" wire:model="email" required>
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                @endif
                {{-- Step 3 --}}

                @if ($step === 3)
                <h5>Step 3: Organization Info</h5>

                <div class="mb-3">
                    <label class="form-label">Organization Name</label>
                    <input type="text" class="form-control" wire:model="company" required>
                    @error('company') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Org Type</label>
                    <select class="form-select" wire:model="companyType" required>
                        <option value="">Select Type</option>
                        <option value="Company">Company</option>
                        <option value="NGO">NGO</option>
                        <option value="Govt">Government</option>
                    </select>
                    @error('companyType') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Org Contact Number</label>
                    <input type="text" class="form-control" wire:model="companyContact" placeholder="923001234567">
                    @error('companyContact') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Org Website</label>
                    <input type="url" class="form-control" wire:model="companyWebsite" placeholder="https://example.org">
                    @error('companyWebsite') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Official Email</label>
                    <input type="email" class="form-control" wire:model="companyEmail">
                    @error('companyEmail') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                @endif

                <div class="d-flex justify-content-between mt-4">
                    @if ($step > 1)
                    <button type="button" wire:click="back" class="btn btn-secondary">Back</button>
                    @endif

                    <button type="submit" class="btn btn-primary">
                        {{ $step === 3 ? 'Submit' : 'Next' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>