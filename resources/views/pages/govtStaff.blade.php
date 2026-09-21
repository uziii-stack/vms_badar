@auth
@extends('layouts.layout')
@section("content")
@if(is_array($govtOrganizationStaff) && count($govtOrganizationStaff) > 0)
<div class="row">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('governmentOrganization.index')}}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$govtOrganization->name}}</li>
        </ol>
    </nav>
</div>
@endif

<div id="toast-container" aria-live="polite" aria-atomic="true" class="position-fixed bottom-0 end-0 p-3"
    style="z-index: 9999;">
</div>

<span id="alert-comp"></span>

<div class="modal fade" id="outputModal" tabindex="-1">
</div>


@if(session()->get('user')->roles[0]->name === "admin" || session()->get('user')->roles[0]->name === "snseaAdmin")
<div class="row">
    <div class="d-flex justify-content-center">
        <a type="button" href="{{route('governmentStaff.create',['orgId'=>$orgId])}}"
            class="btn btn-outline-primary">Add
            Organization Staff</a>
    </div>
</div>
<br />
@endif
<div class="row">
    <div class="d-flex flex-wrap justify-content-start flex-gap-2">
        <button id="btn-1" type="button" class="btn btn-outline-primary mb-1 mx-1"
            onclick="generateModalComponent(this,'programModal','programUids')" disabled>Attach Program</button>
        <button id="btn-2" type="button" class="btn btn-outline-success mb-1 mx-1"
            onclick="generateModalComponent(this,'couponModal','couponUids')" disabled>Attach Coupon</button>
    </div>
</div>
<br />
<div class="row">
    <div class="card w-100">
        <div class="card-body p-4">
            <h5 class="card-title fw-semibold mb-4">Organization Staff</h5>
            <div class="table-responsive">
                <table id="table" data-filter-control-multiple-search="true"
                    data-filter-control-multiple-search-delimiter="," data-virtual-scroll="true" data-flat="true"
                    data-filter-control-multiple-search="true" data-show-refresh="true" data-filter-control="true"
                    data-show-pagination-switch="true" data-click-to-select="true" data-toggle="table"
                    data-url="{{route('api.governmentStaff.index',['orgId'=>$orgId])}}" data-pagination="true"
                    data-show-toggle="true" data-show-export="true" data-show-columns="true" data-unique-id="uid"
                    data-show-columns-toggle-all="true" data-page-list="[10, 25, 50, 100,200]">
                    <thead>
                        <tr>
                            <th data-field="state" data-checkbox="true"></th>
                            <th data-filter-control="input" data-field="SNO" data-formatter="operateSerial"
                                data-sortable="true">S.No.</th>
                            <th data-filter-control="input" data-field="name" data-sortable="true">Name</th>
                            <th data-filter-control="input" data-field="rank.ranks_name" data-sortable="true">Rank</th>
                            <th data-filter-control="input" data-field="staff_categories.name" data-sortable="true">
                                Staff Category</th>
                            <th data-filter-control="input" data-field="designation" data-sortable="true">Designation
                            </th>
                            <th data-filter-control="input" data-field="identity" data-sortable="true">Identity</th>
                            <th data-filter-control="input" data-field="contact" data-sortable="true">Contact</th>
                            <th data-filter-control="input" data-field="code" data-sortable="true">Code</th>
                            <th data-filter-control="input" data-field="invited.name" data-sortable="true">Invited By
                            </th>
                            <th data-filter-control="input" data-field="invitaion_no" data-sortable="true">Invitaion No.
                            </th>
                            <th data-filter-control="input" data-field="address" data-sortable="true">Address</th>
                            <th data-filter-control="input" data-field="staff_country.name" data-sortable="true">Country
                            </th>
                            <th data-filter-control="input" data-field="staff_city.name" data-sortable="true">City</th>
                            <!-- <th data-filter-control="input" data-field="car_sticker_color" data-sortable="true">Color</th> -->
                            <th data-filter-control="input" data-field="car_sticker_no" data-sortable="true"
                                data-formatter="colorTextOperator">Car Sticker Number</th>
                            <th data-filter-control="input" data-field="programs" data-sortable="true"
                                data-formatter='listComponentFormater'>Program Details</th>
                            <th data-filter-control="input" data-field="coupons" data-sortable="true"
                                data-formatter='listComponentFormater'>Coupon Details</th>
                            <th data-field="uid" data-sortable="true" data-formatter='operateDeAttachProgram'>Program
                            </th>
                            <th data-field="uid" data-sortable="true" data-formatter='operateDeAttachCoupon'>Coupon</th>
                            <th data-field="uid" data-formatter="operateEdit" data-force-hide="true"
                                data-force-hide="true" data-print-ignore="true">
                                Edit</th>
                            <th data-field="uid" data-formatter="operateFormatter"
                                data-events="operateOrganizationDelete">
                                Delete</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<br />
@section('scripts')
<script>
    const programs = @json($programs ?? []);
    const coupons = @json($coupons ?? []);
</script>
@endsection
@include("layouts.tableFoot")
<script>
    const $table = $('#table');
    const $btn1 = $('#btn-1'),
        $btn2 = $('#btn-2');
    let selections = [];
    const modalContainer = document.getElementById('outputModal');


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

    function operateFormatter(value, row, index) {
        return [
            `<a class='remove btn btn-badar' href='javascript:void(0)' title='Remove'><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg></a>`
        ]
    }

    function listComponentFormater(value, row, index) {
        return value.length > 0 ? [
            `<ul>
                ${value.map(item => `<li>${item.coupon_name || item.program_name} (${item.program_start_time || item.coupon_validity_start_time}-${item.program_end_time || item.coupon_validity_end_time}) Day ${item.program_day || item.coupon_day}.</li>`).join('')}
            </ul>`
        ] : 'N/A';
    }

    function colorTextOperator(value, row, index) {
        return [
            `<span style="color: ${row.car_sticker_color || 'black'}; background-color:${row.car_sticker_color =="Yellow" || row.car_sticker_color =="Green"?'black':'' };font-weight: bold;">
                ${value || 'N/A'}
            </span>`
        ]
    }

    function operateEdit(value, row, index) {
        let route = '{{route("governmentStaff.edit", ["governmentStaff" => ":id"])}}'.replace(':id', row.uid);
        return [
            '<div class="left">',
            '<a class="btn btn-success" href="' + route + '">',
            '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>',
            '</a>',
            '</div>'
        ].join('')
    }

    function operateSerial(value, row, index) {
        return index + 1;
    }

    function getIdSelections() {
        return $.map($table.bootstrapTable('getSelections'), function(row) {
            return row.uid;
        })
    }

    function operateDeAttachProgram(value, row, index) {
        return row.programs.length > 0 ? [`<button id="btn-3" type="button" class="btn btn-outline-badar mb-1 mx-1"
            onclick="generateModalComponent(this,'programModal','programUids','deAttach','${value}')"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-link-minus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><path d="M11 6l.463 -.536a5 5 0 1 1 7.071 7.072l-.534 .464" /><path d="M12.603 18.534a5.07 5.07 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" /><path d="M16 19h6" /></svg></button>`] : [`N/A`];
    }

    function operateDeAttachCoupon(value, row, index) {
        return row.coupons.length > 0 ? [`<button id="btn-4" type="button" class="btn btn-outline-badar mb-1 mx-1"
            onclick="generateModalComponent(this,'couponModal','couponUids','deAttach','${value}')"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-link-minus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><path d="M11 6l.463 -.536a5 5 0 1 1 7.071 7.072l-.534 .464" /><path d="M12.603 18.534a5.07 5.07 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" /><path d="M16 19h6" /></svg></button>`] : [`N/A`];
    }

    window.operateOrganizationDelete = {
        [`click .remove`]: (e, value, row) => {
            // console.log(row)
            const url = "{{ route('api.governmentStaff.destroy', ':id') }}".replace(':id', row.uid);
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




    $table.on('check.bs.table uncheck.bs.table ' +
        'check-all.bs.table uncheck-all.bs.table',
        function() {
            $btn1.prop('disabled', !$table.bootstrapTable('getSelections').length)
            $btn2.prop('disabled', !$table.bootstrapTable('getSelections').length)

            // save your data, here just save the current page
            selections = getIdSelections()
            // push or splice the selections if you want to save all data selections
        })


    function generateModalComponent(e, modalId, name, type = 'attach', rowId = '') {
        type == 'attach' ? e.disabled = true : null;
        let rowData = type != 'attach' ? $table.bootstrapTable('getRowByUniqueId', rowId) : [];
        if(type != 'attach'){
            $table.bootstrapTable('checkBy', {
               field: 'uid',
               values: [rowId]
           })
           console.log(getIdSelections());
        }

        let programModalRoute = type == 'attach' ? "{{ route('api.governmentStaff.attachProgram') }}" : "{{ route('api.governmentStaff.deAttachProgram') }}";
        let couponModalRoute = type == 'attach' ? "{{ route('api.governmentStaff.attachCoupon') }}" : "{{ route('api.governmentStaff.deAttachCoupon') }}";
        const modalHtml = `<div class="modal-dialog">
        <div class="modal-content" id="${modalId}">
            <form id="${modalId}_form" name="${modalId}_form"
             method="POST" onsubmit="sendingPostRequest(event,'${modalId}','${type}', '${modalId == 'programModal' ?programModalRoute:couponModalRoute}')">
                <div class="modal-header">
                    <h5 class="modal-title">${type == 'attach' ?'Attach':'Deattach'} ${modalId == 'programModal'?'Program':'Coupon'}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <select name="${name}[]" class="form-select" multiple aria-label="Multiple select example">
                    ${type == 'attach' ?
                    (modalId == 'programModal' ?programs.map((program,index)=>`<option value="${program.program_uid}"> ${program.program_name} (${program.program_start_time}-${program.program_end_time}) Day ${program.program_day} </option>`):
                        coupons.map((coupon,index)=>`<option value="${coupon.coupon_uid}">${coupon.coupon_name} (${coupon.coupon_validity_start_time}-${coupon.coupon_validity_end_time}) Day ${coupon.coupon_day} </option>`)):
                        (modalId == 'programModal' ?rowData.programs.map((program,index)=>`<option value="${program.program_uid}"> ${program.program_name} (${program.program_start_time}-${program.program_end_time}) Day ${program.program_day} </option>`):
                        rowData.coupons.map((coupon,index)=>`<option value="${coupon.coupon_uid}">${coupon.coupon_name} (${coupon.coupon_validity_start_time}-${coupon.coupon_validity_end_time}) Day ${coupon.coupon_day} </option>`))};
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Update</button>
                </div>
        </div>
    </div>`;

        // Append modal to container
        modalContainer.innerHTML = "";
        modalContainer.insertAdjacentHTML('beforeend', modalHtml);
        const myModal = new bootstrap.Modal(modalContainer);
        myModal.show();


        // $btn1.prop('disabled', true)
    }

    const sendingPostRequest = (event, modalId, type, route) => {
        event.preventDefault();
        let ids = getIdSelections()
        // console.log(ids)
        const formData = new FormData(event.target);
        const formValues = {
            'staffUids': getIdSelections(),
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
                formValues[key] = [value];
            }
        });

        const lengthOfForm = Object.keys(formValues).length; // Length Of Values getting from from 
        console.log(formValues);
        axios.post(route, formValues)
            .then(response => {
                ids.forEach(uid => {
                    $table.bootstrapTable('updateByUniqueId', {
                        id: uid,
                        row: modalId == 'programModal' ? {
                            programs: response.data.data
                        } : {
                            coupons: response.data.data
                        }
                        // row: modalId == 'programModal' ? (type == 'attach' ? {
                        //     programs: response.data.data
                        // } : {
                        //     programs: []
                        // }) : (type == 'attach' ? {
                        //     coupons: response.data.data
                        // } : {
                        //     coupons: []
                        // })
                    })
                });
                document.getElementById('alert-comp').innerHTML = `
            <div class="alert alert-${response.data.success ? 'success' : 'danger'} alert-dismissible fade show" role="alert">
                <strong>${response.data.message}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;

                if (response.data.success || response.success) {
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
                $table.bootstrapTable('uncheckAll');
                console.log('Request processing completed.');
            });
    }
</script>
@endsection
@endauth