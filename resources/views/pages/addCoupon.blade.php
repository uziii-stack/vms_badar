@auth
@extends('layouts.layout')
@section("content")
<span id="alert-comp"></span>
<div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-4">New Coupon</h5>
                <div class="table-responsive">
                    <form name="couponBasicInfo" id="couponBasicInfo" method="POST"
                        onsubmit='sendingPostRequest(event,`{{isset($coupon)?route("request.updateCoupon",$coupon->coupon_uid):route("request.addCoupon")}}`)'>
                        <fieldset>
                            <legend>Add Coupon Form</legend>
                            @csrf
                            <div class="mb-3">
                                <label for="coupon_name" class="form-label">Coupon Name</label>
                                <input name="coupon_name" type="text" class="form-control" id="coupon_name"
                                    placeholder="Coupon Name" value="{{isset($coupon) ? $coupon->coupon_name : ''}}" />
                            </div>
                            <div class="mb-3">
                                <label for="coupon_day" class="form-label">Coupon Day</label>
                                <input name="coupon_day" type="number" class="form-control" id="coupon_day"
                                    placeholder="Coupon Day ( 1 - 2 - 3)" min="1" max="10"
                                    value="{{isset($coupon) ? $coupon->coupon_day : ''}}" />
                            </div>
                            <div class="mb-3">
                                <label for="coupon_validity_start_time" class="form-label">Coupon Validity Start
                                    Time</label>
                                <input name="coupon_validity_start_time" type="number" class="form-control"
                                    id="coupon_validity_start_time"
                                    placeholder="Coupon Validity Start Time (0000 - 2359)" minlength="4" maxlength="4"
                                    min="0000" max="2359"
                                    value="{{isset($coupon) ? $coupon->coupon_validity_start_time : ''}}" />
                            </div>
                            <div class="mb-3">
                                <label for="coupon_validity_end_time" class="form-label">Coupon Validity End
                                    Time</label>
                                <input name="coupon_validity_end_time" type="number" class="form-control"
                                    id="coupon_validity_end_time" placeholder="Coupon Validity End Time (0000 - 2359)"
                                    minlength="4" maxlength="4" min="0000" max="2359"
                                    value="{{isset($coupon) ? $coupon->coupon_validity_end_time : ''}}" />
                            </div>
                            <br />
                            <button type="submit" class="btn btn-{{isset($coupon) ? 'success' : 'primary'}}"
                                data-bs-dismiss="modal">{{isset($coupon) ? 'Update' : 'Add'}} Coupon</button>
                            {{-- <input type="submit" name="submit" class="btn btn-primary" value="Add Coupon" /> --}}
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