<div class="col-md-2">
    @if($isNew)
    <button class="btn btn-outline-primary" wire:click="toggleModal" style="font-size: 24px;"><i
            class="ti ti-user-plus"></i></button>
    @else
    <button class="btn btn-outline-success" wire:click="toggleModal" style="font-size: 24px;"><i
            class="ti ti-edit"></i></button>
    @endif
    <div class="modal fade @if($isOpen) show @endif" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        style="display: @if($isOpen) block @else none @endif;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Attandee</h5>
                    <button type="button" class="btn-close" wire:click="toggleModal" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="@if($isNew)addNew @else update @endif">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="attandeeName" class="form-label">Name</label>
                                    <input type="text" wire:model='name'
                                        class="form-control @error('name') is-invalid @enderror" id="attandeeName"
                                        placeholder="Enter Name" required>
                                    <div class="invalid-feedback">
                                        @error('name')<span class="error">
                                            {{ $message }}
                                        </span>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company" class="form-label">Company/Institute</label>
                                    <input type="text" wire:model='company'
                                        class="form-control @error('company') is-invalid @enderror"
                                        id="company" placeholder="Enter Company Name" required>
                                    <div class="invalid-feedback">
                                        @error('company')<span class="error">
                                            {{ $message }}
                                        </span>@enderror
                                    </div>
                                </div>
                            </div>
                            <!-- @ & { } are missing in below code -->
                            <!-- <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="attandeeDob" class="form-label">Attandee DOB</label>
                                    <input type="date" wire:model='dob' class="form-control error('dob') is-invalid enderror" id="attandeeDob" max="$maxDate" required>
                                    <div class="invalid-feedback">
                                        error('dob')<span class="error">$message </span>enderror
                                    </div>
                                </div>
                            </div> -->
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="attandeeDesignation" class="form-label">Designation</label>
                                    <input type="text" wire:model='designation'
                                        class="form-control @error('designation') is-invalid @enderror"
                                        id="attandeeDesignation" placeholder="Enter Designation" required>
                                    <div class="invalid-feedback">
                                        @error('designation')<span class="error">
                                            {{ $message }}
                                        </span>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="attandeeIdentity" class="form-label">CNIC / Passport</label>
                                    <input type="text" wire:model='identity'
                                        class="form-control @error('identity') is-invalid @enderror"
                                        pattern="^[a-zA-Z0-9]{9,13}$" id="attandeeIdentity"
                                        placeholder="Enter Identity">
                                    <div class="invalid-feedback">
                                        @error('identity')<span class="error">
                                            {{ $message }}
                                        </span>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="attandeeContact" class="form-label">Contact</label>
                                    <input type="text" wire:model="contact"
                                        class="form-control @error('contact') is-invalid @enderror" id="attandeeContact"
                                        placeholder="Enter Contact Number">
                                    <div class="invalid-feedback">
                                        @error('contact')<span class="error">
                                            {{ $message }}
                                        </span>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="attandeeEmail" class="form-label">Email</label>
                                    <input type="text" wire:model='email'
                                        class="form-control @error('email') is-invalid @enderror" id="attandeeEmail"
                                        placeholder="Enter Email Address">
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
                                    <label for="attandeePMDC" class="form-label">PMDC No</label>
                                    <input type="text" wire:model='attandeePMDC'
                                        class="form-control @error('attandeePMDC') is-invalid @enderror" id="attandeePMDC"
                                        placeholder="Enter PMDC No" maxlength="50">
                                    <div class="invalid-feedback">
                                        @error('attandeePMDC')<span class="error">
                                            {{ $message }}
                                        </span>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="event" class="form-label">Event</label>
                                    <select wire:model='eventName' class="form-select @error('event') is-invalid @enderror" id="event">
                                        <option value="" selected disabled hidden> Select Event </option>
                                        @foreach(\App\Models\Event::active()->get() as $event)
                                        <option value="{{ $event->name }}">{{ $event->display_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        @error('event')<span class="error">
                                            {{ $message }}
                                        </span>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="submit-button" class="form-label">{{$isNew ? 'Save' :
                                            'Update'}}</label>
                                    <br />
                                    <button type="submit"
                                        class="form-control btn btn-outline-{{$isNew ? 'success' : 'warning'}}"
                                        id="submit-button"> {{$isNew?'Save':'Update'}} </button>
                                    @if(session()->has('message'))
                                    <div class="alert alert-success mt-3">
                                        {{ session('message') }}
                                    </div>
                                    @elseif(session()->has('error'))
                                    <div class="alert alert-danger mt-3">
                                        {{ session('error') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="modal-footer">
                        if($isNew)
                        <button type="submit" class="btn btn-outline-success">Add</button>
                        else
                        <button type="submit" class="btn btn-outline-warning">Update</button>
                        endif
                        if(session()->has('message'))
                        <div class="alert alert-success mt-3">
                             session('message') 
                        </div>
                        endif
                        if(session()->has('error'))
                        <div class="alert alert-danger mt-3">
                             session('error') 
                        </div>
                        endif
                    </div> -->
                </form>
            </div>
        </div>
    </div>
