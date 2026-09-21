@auth
@extends('layouts.layout')
@section("content")


<div id="toast-container" aria-live="polite" aria-atomic="true" class="position-fixed bottom-0 end-0 p-3"
    style="z-index: 9999;">
</div>
<span id="alert-comp"></span>
@if(session()->get('user')->roles[0]->name === "admin" || session()->get('user')->roles[0]->name === "snseaAdmin")
<div class="row">
    <div class="d-flex justify-content-center">
        <a type="button" href="{{route('governmentOrganization.create')}}" class="btn btn-outline-primary">Add
            Organization</a>
    </div>
</div>
<br />
@endif
<div class="row">
    <div class="card w-100">
        <div class="card-body p-4">
            <h5 class="card-title fw-semibold mb-4">Organization</h5>
            <div class="table-responsive">
                <table id="table" data-filter-control="true" data-filter-control-multiple-search="true"
                    data-filter-control-multiple-search-delimiter="," data-virtual-scroll="true" data-flat="true"
                    data-show-refresh="true" data-show-pagination-switch="true" data-click-to-select="true"
                    data-toggle="table" data-url="{{route('api.governmentOrganization.index')}}" data-pagination="true"
                    data-show-toggle="true" data-show-export="true" data-show-columns="true"
                    data-show-columns-toggle-all="true" data-page-list="[10, 25, 50, 100,200]">
                    <thead>
                        <tr>
                            <th data-filter-control="input" data-field="SNO" data-formatter="operateSerial"
                                data-sortable="true">S.No.</th>
                            <th data-filter-control="input" data-field="name" data-sortable="true">Name</th>
                            <th data-field="address" data-filter-control="input" data-sortable="true">Address</th>
                            <th data-field="govt_org_country.name" data-filter-control="input" data-sortable="true">
                                Country</th>
                            <th data-field="govt_org_city.name" data-filter-control="input" data-sortable="true">City
                            </th>
                            <th data-field="code" data-filter-control="input" data-sortable="true">Code</th>
                            <th data-field="govt_org_group.name" data-filter-control="input" data-sortable="true">Group
                            </th>
                            <th data-field="ref_no" data-filter-control="input" data-sortable="true">Reference</th>
                            <th data-field="allowed_quantity" data-filter-control="input" data-sortable="true">Allowed
                                Quantity</th>
                            <th data-field="govt_staff" data-filter-control="input" data-formatter="operateQuantity"
                                data-sortable="true">Quantity Occupied</th>
                            <th data-field="head_name" data-filter-control="input" data-sortable="true">Head Name</th>
                            <th data-field="head_contact" data-filter-control="input" data-sortable="true">Head Contact
                            </th>
                            <th data-field="head_email" data-filter-control="input" data-sortable="true">Head Email</th>
                            <th data-field="uid" data-formatter="operateStaff" data-force-hide="true"
                                data-force-hide="true" data-print-ignore="true">
                                Staff</th>
                            <th data-field="uid" data-formatter="operateEdit" data-force-hide="true"
                                data-force-hide="true" data-print-ignore="true">
                                Edit</th>
                            <th data-field="uid" data-formatter="operateSendEmail">Credentials</th>
                            <th data-formatter="operateFormatter" data-events="operateOrganizationDelete">
                                Delete</th>

                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<br />

@include("layouts.tableFoot")
<script>
    function showDeleteToast(message) {
        // Create unique ID for the toast
        const toastId = `toast-${Date.now()}`;
        // Toast HTML
        const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
        // Append toast to container
        const container = document.getElementById('toast-container');
        container.innerHTML = "";
        container.insertAdjacentHTML('beforeend', toastHtml);

        // Initialize and show the toast
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement);
        toast.show();

        // Remove toast from DOM after hidden
        toastElement.addEventListener('hidden.bs.toast', () => {
            toastElement.remove();
        });
    }

    const $table = $('#table');

    function operateFormatter() {
        return [
            '<a class="remove btn btn-badar" href="javascript:void(0)" title="Remove">',
            '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>',
            '</a>'
        ].join('');
    }

    window.operateOrganizationDelete = {
        ['click .remove']: function(e, value, row) {
            console.log(row)
            const url = "{{ route('api.governmentOrganization.destroy', ':id') }}".replace(':id', row.uid);
            axios.delete(url, {
                id: [row.uid]
            }).then(
                function(response) {
                    console.log(response)
                    showDeleteToast(response.data.message)
                }).catch(function(error) {
                console.log(error);
            })
            $table.bootstrapTable('refresh');

        }
    }

    function operateEdit(value, row, index) {
        let route = '{{route("governmentOrganization.edit", ":id")}}'.replace(':id', row.uid);
        return [
            '<div class="left">',
            '<a class="btn btn-success" href="' + route + '">',
            '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>',
            '</a>',
            '</div>'
        ].join('')
    }

    function operateStaff(value, row, index) {
        let route = '{{route("governmentStaff.index",["orgId"=>"id"])}}'.replace("id", row.uid);
        return [
            '<div class="left">',
            '<a class="btn btn-primary" href="' + route + '">',
            '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-users"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>',
            '</a>',
            '</div>'
        ].join('')
    }

    function operateQuantity(value,row,index){
        // console.log(value.length);
        return `${value.length}`;
    }

    function operateSerial(value, row, index) {
        return index + 1;
    }

        function submitEmailRequest(e,route){
            e.disabled = true;
            e.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...`;
            axios({
                method: 'POST',
                url: route,
                data: {},
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => {
                // console.log(response);
                document.getElementById('alert-comp').innerHTML = `
                <div class="alert alert-${response.data ? 'success' : 'danger'} alert-dismissible fade show" role="alert">
                    <strong>Credentials has been sent</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;

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
                e.disabled = false;
                e.innerHTML = `<span><i class="ti ti-mail" style="font-size:24px;"></i></span>`;
                console.log('Request processing completed.');
            });
    }

        function operateSendEmail(value,row,index){
        let route = '{{route("request.sendMailAgain", ":uid")}}'.replace(":uid",value);
        return `<button type="button" class="btn btn-warning" aria-label="Send" onClick="submitEmailRequest(this,'${route}')"><span><i class="ti ti-mail" style="font-size:24px;"></i></span></button>`;

        }

</script>
@endsection
@endauth