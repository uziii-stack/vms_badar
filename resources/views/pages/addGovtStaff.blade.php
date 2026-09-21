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
@if(isset($govtOrganization))
<div class="row">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('governmentOrganization.index')}}">Home</a></li>
            <li class="breadcrumb-item"><a
                    href="{{route('governmentStaff.index',['orgId'=>$orgId])}}">{{$govtOrganization->name}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">New Staff</li>
        </ol>
    </nav>
</div>
@elseif(isset($govtOrganizationStaff))
<div class="row">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('governmentOrganization.index')}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{route('governmentStaff.index',['orgId'=>$govtOrganizationStaff->govt_org_uid])}}">{{$govtOrganizationStaff->govt_org->name}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$govtOrganizationStaff->name}}</li>
        </ol>
    </nav>
</div>
@endif
<div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <form name="organizationStaffInfo" id="organizationStaffInfo" method="POST" onsubmit="sendingPostRequest(event,`{{
                    isset($govtOrganizationStaff->uid)? 
                    route('api.governmentStaff.update',$govtOrganizationStaff->uid):
                    route('api.governmentStaff.store')}}`,`{{isset($govtOrganizationStaff->uid)?'put':'post' }}`)">
                        <fieldset>
                            <legend>Add Govt Staff</legend>
                            @csrf
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="ranks_uid" class="form-label">Rank *</label>
                                            <select name="ranks_uid" id="ranks_uid" class="form-select">
                                                <option value="" selected disabled hidden> Select Rank
                                                </option>
                                                @foreach (\App\Models\Rank::all() as $rank)
                                                <option value="{{$rank->ranks_uid}}" {{isset($govtOrganizationStaff->
                                                    rank->ranks_uid) ? ($govtOrganizationStaff->rank->ranks_uid ==
                                                    $rank->ranks_uid ? 'selected' : '')
                                                    : ''}}>{{$rank->ranks_name}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Full Name *</label>
                                            <input name="name" type="text" class="form-control" id="name"
                                                placeholder="Name"
                                                value="{{isset($govtOrganizationStaff) ? $govtOrganizationStaff->name : ''}}"
                                                required />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="designation" class="form-label">Designation *</label>
                                            <input name="designation" type="text" class="form-control" id="designation"
                                                placeholder="Designation"
                                                value="{{isset($govtOrganizationStaff) ? $govtOrganizationStaff->address : ''}}"
                                                required />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="identity" class="form-label">CNIC / Passport Number *</label>
                                            <input name="identity" type="text" class="form-control" id="identity"
                                                placeholder="CNIC / Passport Number"
                                                value="{{isset($govtOrganizationStaff) ? $govtOrganizationStaff->identity : ''}}"
                                                onchange="isNumeric('identity')" title="Identity Number" required
                                                maxlength="15" required />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <input name="address" type="text" class="form-control" id="address"
                                                placeholder="Address"
                                                value="{{isset($govtOrganizationStaff) ? $govtOrganizationStaff->address : ''}}"
                                                required />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="contact" class="form-label">Contact Number *</label>
                                            <input name="contact" type="number" minlength='14' maxlength='14'
                                                class="form-control" id="contact" placeholder="Contact Number"
                                                value="{{isset($govtOrganizationStaff) ? $govtOrganizationStaff->contact : ''}}"
                                                minlength='0' maxlength='14' title="14 DIGIT PHONE NUMBER" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="invited_by" class="form-label">Staff
                                                Invited by *</label>
                                            <select name="invited_by" id="invited_by" class="form-select">
                                                <option value="" selected disabled hidden> Select Invited By
                                                </option>
                                                @foreach (\App\Models\Invitees::all() as $invitees)
                                                <option value="{{$invitees->id}}" {{isset($govtOrganizationStaff->
                                                    invited->id ) ? ($govtOrganizationStaff->invited->id ==
                                                    $invitees->id ? 'selected' : '')
                                                    : ''}}>{{$invitees->name}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="staff_category" class="form-label">Staff Category *
                                            </label>
                                            <select name="staff_category" id="staff_category" class="form-select">
                                                <option value="" selected disabled hidden>Select Staff Category
                                                </option>
                                                @foreach (\App\Models\StaffCategory::all() as $staffCat)
                                                <option value="{{$staffCat->id}}" {{isset($govtOrganizationStaff->
                                                    staff_categories->id ) ?
                                                    ($govtOrganizationStaff->staff_categories->id ==
                                                    $staffCat->id ? 'selected' : '')
                                                    : ''}}>{{$staffCat->name}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="country" class="form-label">Country *</label>
                                            <select name="country" id="country" class="form-select">
                                                <option value="" selected disabled hidden> Select Country
                                                </option>
                                                @foreach (\App\Models\Country::all() as $country)
                                                <option value="{{$country->id}}" {{isset($govtOrganizationStaff->
                                                    staff_country->id) ? ($govtOrganizationStaff->staff_country->id ==
                                                    $country->id ? 'selected' : '')
                                                    : ''}}>{{$country->name}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="city" class="form-label">City *</label>
                                            <select name="city" id="city" class="form-select">
                                                <option value="" selected disabled hidden> Select City
                                                </option>
                                                @foreach (\App\Models\Cities::all() as $city)
                                                <option value="{{$city->id}}" {{
                                                    isset($govtOrganizationStaff->city) ?
                                                    ($govtOrganizationStaff->city == $city->id ? 'selected' : ''): ''
                                                    }}>
                                                    {{$city->name}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="car_sticker_no" class="form-label">Car Sticker No.</label>
                                            <input name="car_sticker_no" type="text" minlength='4' maxlength='11'
                                                class="form-control" id="car_sticker_no"
                                                placeholder="Car Sticker Number"
                                                value="{{isset($govtOrganizationStaff) ? $govtOrganizationStaff->car_sticker_no : ''}}"
                                                minlength='4' maxlength='11' />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="car_sticker_color" class="form-label">Car Sticker Color</label>
                                            <select name="car_sticker_color" id="car_sticker_color" class="form-select">
                                                <option value="" selected disabled hidden> Select Car Sticker Color
                                                </option>
                                                <option value="Red" {{isset($govtOrganizationStaff->
                                                    car_sticker_color) ? ($govtOrganizationStaff->car_sticker_color ==
                                                    "Red"? 'selected' : '')
                                                    : ''}}>{{isset($govtOrganizationStaff->car_sticker_color)&&
                                                    $govtOrganizationStaff->car_sticker_color ==
                                                    "Red"
                                                    ?$govtOrganizationStaff->car_sticker_color:"Red"}}
                                                <option value="Blue" {{isset($govtOrganizationStaff->
                                                    car_sticker_color) ? ($govtOrganizationStaff->car_sticker_color ==
                                                    "Blue"? 'selected' : '')
                                                    : ''}}>{{isset($govtOrganizationStaff->car_sticker_color)&&
                                                    $govtOrganizationStaff->car_sticker_color ==
                                                    "Blue"
                                                    ?$govtOrganizationStaff->car_sticker_color:"Blue"}}
                                                <option value="Yellow" {{isset($govtOrganizationStaff->
                                                    car_sticker_color) ? ($govtOrganizationStaff->car_sticker_color ==
                                                    "Yellow"? 'selected' : '')
                                                    : ''}}>{{isset($govtOrganizationStaff->car_sticker_color)&&
                                                    $govtOrganizationStaff->car_sticker_color ==
                                                    "Yellow"
                                                    ?$govtOrganizationStaff->car_sticker_color:"Yellow"}}
                                                <option value="Green" {{isset($govtOrganizationStaff->
                                                    car_sticker_color) ? ($govtOrganizationStaff->car_sticker_color ==
                                                    "Green"? 'selected' : '')
                                                    : ''}}>{{isset($govtOrganizationStaff->car_sticker_color)&& $govtOrganizationStaff->car_sticker_color ==
                                                    "Green"
                                                    ?$govtOrganizationStaff->car_sticker_color:"Green"}}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="invitaion_no" class="form-label">Invitaion No.</label>
                                            <input name="invitaion_no" type="text" class="form-control"
                                                id="invitaion_no" placeholder="AE21466"
                                                value="{{isset($govtOrganizationStaff) ? $govtOrganizationStaff->invitaion_no : ''}}"
                                                title="Invitaion Number" minlength="5" maxlength="10"
                                                autocomplete="invitation_no" required />
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <input type="submit" name="submitMore"
                                                class="btn {{isset($govtOrganizationStaff->uid )?'btn-primary':'btn-success'}}"
                                                value="{{isset($govtOrganizationStaff->uid)?'Update Govt Staff & More':'Add Govt Staff & More'}}" />
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
        let orgID = '{{isset($orgId) ? $orgId : $staffId}}';
        event.preventDefault();
        const formData = new FormData(event.target);
        const formValues = {
            'govt_org_uid': orgID,
        };

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