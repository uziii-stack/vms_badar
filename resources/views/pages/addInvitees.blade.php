@auth
@extends('layouts.layout')
@section("content")
<span id="alert-comp"></span>
<div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-4">New Invitee</h5>
                <div class="table-responsive">
                    <form name="inviteeInfo" id="inviteeInfo" method="POST" onsubmit="sendingPostRequest(event,`{{
                    isset($invitee->uid)? route('api.invitees.update',$invitee->uid):route('api.invitees.store')
                    }}`,`{{isset($invitee->uid)?'put':'post' }}`)">
                        <fieldset>
                            <legend>Add Invitee Form</legend>
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Invitee Name *</label>
                                <input name="name" type="text" class="form-control" id="name"
                                    placeholder="Invitee Name" value="{{isset($invitee) ? $invitee->name : ''}}" />
                            </div>
                            <div class="mb-3">
                                <label for="ranks_uid" class="form-label">Invitee Rank *</label>
                                <select name="ranks_uid" id="ranks_uid" class="form-select" required>
                                    <option value="" disabled hidden> Select Rank </option>
                                    @foreach (\App\Models\Rank::all() as $key=>$rank)
                                    <option value="{{$rank->ranks_uid}}" {{isset($invitee)&& $invitee->rank['ranks_uid']
                                        == $rank->ranks_uid ? 'selected' : ''}}> {{$rank->ranks_name}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="designation" class="form-label">Invitee Designation *</label>
                                <input name="designation" type="text" class="form-control" id="designation"
                                    placeholder="Invitee Designation" value="{{isset($invitee) ? $invitee->designation : ''}}" />
                            </div>
                            <button type="submit" class="btn btn-{{isset($invitee) ? 'success' : 'primary'}}" data-bs-dismiss="modal">{{isset($invitee) ? 'Update' : 'Add'}} Invitee</button>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const sendingPostRequest = (event, route) => {
        event.preventDefault();
        const methodType = '{{isset($invitee) ? "put":"post"}}'; // Determine the method type based on the presence of $invitee
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

        const lengthOfForm = Object.keys(formValues).length; // Length Of Values getting from from 

        // axios.post(route, formValues)
        axios({
                method: methodType,
                url: route,
                data: formValues,
                headers: {
                    'Content-Type': 'application/json',
                }
            }).then(response => {
                console.log(response);
                document.getElementById('alert-comp').innerHTML = `
                <div class="alert alert-${response.data.success ? 'success' : 'danger'} alert-dismissible fade show" role="alert">
                    <strong>${response.data.message}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;

                if (response.data.success && methodType !== 'put') {
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
</script>
@endsection
@endauth