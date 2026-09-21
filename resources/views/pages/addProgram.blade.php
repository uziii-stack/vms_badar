@auth
@extends('layouts.layout')
@section("content")
<span id="alert-comp"></span>
<div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-4">New Program</h5>
                <div class="table-responsive">
                    <form name="programBasicInfo" id="programBasicInfo" method="POST"
                        onsubmit='sendingPostRequest(event,`{{isset($program)?route("request.updateProgram",$program->program_uid):route("request.addProgram")}}`)'>
                        <fieldset>
                            <legend>Add Program Form</legend>
                            @csrf
                            <div class="mb-3">
                                <label for="program_name" class="form-label">Program Name</label>
                                <input name="program_name" type="text" class="form-control" id="program_name"
                                    placeholder="Program Name"
                                    value="{{isset($program) ? $program->program_name : ''}}" />
                            </div>
                            <div class="mb-3">
                                <label for="program_day" class="form-label">Program Day</label>
                                <input name="program_day" type="number" class="form-control" id="program_day"
                                    value="{{isset($program) ? $program->program_day : ''}}"
                                    placeholder="Program Day ( 1 - 2 - 3)" min="1" max="10" />
                            </div>
                            <div class="mb-3">
                                <label for="program_start_time" class="form-label">Program Start Time</label>
                                <input name="program_start_time" type="number" class="form-control"
                                    id="program_start_time" placeholder="Program Start Time (0000 - 2359)" minlength="4"
                                    maxlength="4" min="0000" max="2359" value="{{isset($program) ? $program->program_start_time : ''}}"/>
                            </div>
                            <div class="mb-3">
                                <label for="program_end_time" class="form-label">Program End Time</label>
                                <input name="program_end_time" type="number" class="form-control" id="program_end_time"
                                    placeholder="Program End Time (0000 - 2359)" minlength="4" maxlength="4" min="0000"
                                    max="2359" value="{{isset($program) ? $program->program_end_time : ''}}"/>
                            </div>
                            <br />
                            <button type="submit" class="btn btn-{{isset($program) ? 'success' : 'primary'}}" data-bs-dismiss="modal">{{isset($program) ? 'Update' : 'Add'}} Program</button>
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

    axios.post(route, formValues)
        .then(response => {
            console.log(response);
            document.getElementById('alert-comp').innerHTML = `
                <div class="alert alert-${response.data.success ? 'success' : 'danger'} alert-dismissible fade show" role="alert">
                    <strong>${response.data.message}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;

        //         if (response.data.success) {
        //     event.target.reset();
        // }
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