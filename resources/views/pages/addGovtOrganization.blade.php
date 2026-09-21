@auth
@extends('layouts.layout')
@section("content")
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/cropper/2.3.4/cropper.min.css'>
<style>
    .box {
        padding: 0.5em;
        width: 100%;
        margin: 0.5em;
    }

    .box-2 {
        padding: 0.5em;
        width: calc(100%/2 - 1em);
    }

    .hide {
        display: none;
    }

    img {
        max-width: 100%;
    }
</style>
<span id="alert-comp"></span>
<div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <form name="organizationInfo" id="organizationInfo" method="POST" onsubmit="sendingPostRequest(event,`{{
                    isset($organization->uid)? 
                    route('api.governmentOrganization.update',$organization->uid):
                    route('api.governmentOrganization.store')}}`,`{{isset($organization->uid)?'put':'post' }}`)">
                        <fieldset>
                            <legend>Add Organization</legend>
                            @csrf
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-4">
                                        @if(session()->get('user')->roles[0]->name === "admin")
                                        @if(isset($organization))
                                        <livewire:modal-form-component wire:id="{{rand()}}" wire:key="{{rand()}}"
                                            modalId="group" name="Organization Group *"
                                            :className="$modelClass=App\Models\Group::class" colorClass="danger"
                                            :oldData='$organization->group' btnName="Add Group" outPut="id" />

                                        @else
                                        <livewire:modal-form-component wire:id="{{rand()}}" wire:key="{{rand()}}"
                                            modalId="group" name="Organization Group *"
                                            :className="$modelClass=App\Models\Group::class" colorClass="primary"
                                            :oldData='null' btnName="Add Group" outPut="id" />
                                        @endif
                                        @else
                                        <div class="mb-3">
                                            <label for="group" class="form-label">Organization
                                                Group</label>
                                            <select name="group" id="group" class="form-select" required>
                                                <option value="" selected disabled hidden> Select Organization
                                                    Group
                                                </option>
                                                @foreach (\App\Models\Group::all() as $group)
                                                <option value="{{$group->id}}" {{isset($organization->
                                                    group) ? ($organization->group ==
                                                    $group->name ? 'selected' : '')
                                                    : ''}}>{{isset($organization->group)
                                                    ?$organization->group:$group->name}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Organization Name *</label>
                                            <input name="name" type="text" class="form-control" id="name"
                                                placeholder="Organization Name *"
                                                value="{{isset($organization) ? $organization->name : ''}}" required />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Organization Address</label>
                                            <input name="address" type="text" class="form-control" id="address"
                                                placeholder="Organization Address"
                                                value="{{isset($organization) ? $organization->address : ''}}"
                                                />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        @if(session()->get('user')->roles[0]->name === "admin")
                                        @if(isset($organization))
                                        <livewire:modal-form-component wire:id="{{rand()}}" wire:key="{{rand()}}"
                                            modalId="country" name="Organization Country *"
                                            :className="$modelClass=App\Models\Country::class" colorClass="warning"
                                            :oldData='$organization->country' btnName="Add Country" outPut="id" />
                                        @else
                                        <livewire:modal-form-component wire:id="{{rand()}}" wire:key="{{rand()}}"
                                            modalId="country" name="Organization Country *"
                                            :className="$modelClass=App\Models\Country::class" colorClass="warning"
                                            :oldData='null' btnName="Add Country" outPut="id" />
                                        @endif
                                        @else
                                        <div class="mb-3">
                                            <label for="country" class="form-label">Country *</label>
                                            <select name="country" id="country" class="form-select" required>
                                                <option value="" selected disabled hidden> Select Country
                                                </option>
                                                @foreach (\App\Models\Country::all() as $country)
                                                <option value="{{$country->id}}" {{isset($organization->
                                                    country) ? ($organization->country ==
                                                    $country->name ? 'selected' : '')
                                                    : ''}}>{{isset($organization->country)
                                                    ?$organization->country:$country->name}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="city" class="form-label">City *</label>
                                            <select name="city" id="city" class="form-select" required>
                                                <option value="" selected disabled hidden> Select City
                                                </option>
                                                @foreach (\App\Models\Cities::all() as $City)
                                                <option value="{{$City->id}}" {{isset($organization->
                                                    city) ? ($organization->city ==
                                                    $City->id ? 'selected' : '')
                                                    : ''}}>{{$City->name}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="head_contact" class="form-label">Company Phone/Mobile
                                                Number</label>
                                            <input name="head_contact" type="text" minlength='11' maxlength='11'
                                                class="form-control" id="head_contact"
                                                placeholder="Company Contact Number"
                                                value="{{isset($organization) ? $organization->head_contact : ''}}"
                                    minlength='0' maxlength='14' onchange="isContact('contact')"
                                    title="14 DIGIT PHONE NUMBET" data-inputmask="'mask': '+99-9999999999'"
                                    required />
                                </div>
                            </div> --}}
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="ref_no" class="form-label">Organization Reference No.</label>
                                    <input name="ref_no" type="text" minlength='4' maxlength='11'
                                        class="form-control" id="ref_no" placeholder="Organization Reference Number"
                                        value="{{isset($organization) ? $organization->head_contact : ''}}"
                                        minlength='4' maxlength='11' />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="allowed_quantity" class="form-label">Staff Quantity *</label>
                                    <input name="allowed_quantity" type="number" class="form-control"
                                        id="allowed_quantity" placeholder="5"
                                        value="{{isset($organization) ? $organization->allowed_quantity : ''}}"
                                        title="Staff Quanity" min="1" max="2000" maxlength="3" required />
                                </div>
                            </div>
                </div>
                <div class="row"></div>
                <br />
                <br />
                <br />
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="head_name" class="form-label">Organization Representative Name</label>
                            <input name="head_name" type="text" class="form-control" id="head_name"
                                placeholder="Organization Representative Name"
                                value="{{isset($organization) ? $organization->head_name : ''}}"
                                />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="head_contact" class="form-label">Organization Representative
                                Contact</label>
                            <input name="head_contact" type="tel" class="form-control" id="head_contact"
                                placeholder="Company Representative Contact"
                                value="{{isset($organization) ? $organization->head_contact: ''}}"
                                pattern="\d{11}" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="head_email" class="form-label">Organization Representative
                                Email</label>
                            <input name="head_email" type="text" class="form-control" id="head_email"
                                placeholder="Organization Representative Email"
                                value="{{isset($organization) ? $organization->head_email  : ''}}"
                                {{isset($organization) ? 'disabled' : '' }} />
                        </div>
                    </div>
                </div>
                <br />
                <br />
                <div class="row">
                    <div class="col-md-2">
                        <div class="mb-3">
                            <input type="submit" name="submitMore"
                                class="btn {{isset($organization->uid )?'btn-primary':'btn-success'}}"
                                value="{{isset($organization->uid)?'Update Organization & More':'Add Organization & More'}}" />
                        </div>
                    </div>
                </div>
            </div>
            </fieldset>
            </form>

        </div>
    </div>
</div>
</div>
</div>
<script>
    const sendingPostRequest = (event, route, type = 'post') => {
        event.preventDefault();
        const formData = new FormData(event.target);
        const formValues = {};

        // Process each entry in FormData
        formData.forEach((value, key) => {
            // Check if the key already exists in formValues
            if (formValues[key]) {
                // If the key already exists, ensure it is an array and add the new value
                if (Array.isArray(formValues[key])) {
                    formValues[key].push(value);
                } else {
                    formValues[key] = [formValues[key], value];
                }
            } else {
                // If the key does not exist, simply add it
                formValues[key] = value;
            }
        });
        console.log(formValues)
        const lengthOfForm = Object.keys(formValues).length; // Length Of Values getting from from 
        if (type == 'post') {
            axios.post(route, formValues)
                .then(response => {
                    console.log(response);
                    document.getElementById('alert-comp').innerHTML = `
            <div class="alert alert-${response.data.success ? 'success' : 'danger'} alert-dismissible fade show" role="alert">
                <strong>${response.data.message}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;

                    if (response.data.success) {
                        event.target.reset();
                    }
                })
                .catch(error => {
                    console.log(error);
                    document.getElementById('alert-comp').innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>An error occurred while processing your request.</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`;
                })
                .finally(() => {
                    console.log('Request processing completed.');
                });
        } else {
            axios.put(route, formValues).then(response => {
                    console.log(response);
                    document.getElementById('alert-comp').innerHTML = `
            <div class="alert alert-${response.data.success ? 'success' : 'danger'} alert-dismissible fade show" role="alert">
                <strong>${response.data.message}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;

                    if (!response.data.success) {
                        event.target.reset();
                    }
                })
                .catch(error => {
                    console.log(error);
                    document.getElementById('alert-comp').innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>An error occurred while processing your request.</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`;
                })
                .finally(() => {
                    console.log('Request processing completed.');
                });
        }
    }
</script>
@endsection
@endauth