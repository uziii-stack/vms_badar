@auth
@extends('layouts.layout')
@section("content")


<div id="toast-container" aria-live="polite" aria-atomic="true" class="position-fixed bottom-0 end-0 p-3"
    style="z-index: 9999;">
</div>
<span id="alert-comp"></span>

<div class="mb-3">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog"
                    aria-labelledby="ModalFormLabel-edit-modal" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content text-capitalize">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalFormLabel-edit-modal"></h5>
                            </div>
                            <div class="modal-body">
                                <form id="edit-form" name="edit-form">
                                    <div class="form-group">
                                        <label for="name" class="col-form-label">
                                            Name
                                        </label>
                                        <input type="text" name='name' class="form-control" id="name">
                                    </div>
                                    <br />
                                    <div class="form-group">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary"
                                            data-bs-dismiss="modal">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session()->get('user')->roles[0]->name === "admin" || session()->get('user')->roles[0]->name === "snseaAdmin")
<div class="row">
    <div class="d-flex justify-content-center">
        <livewire:essential-modal-form-component wire:id="{{rand()}}" wire:key="{{rand()}}" modalId="add_country"
            name="Country" colorClass="success" :className="$modelClass=App\Models\Country::class" tableId="table1"
            btnName="Add Country" />

    </div>
</div>
<br />
@endif
<div class="row">
    <div class="card w-100">
        <div class="card-body p-4">
            <h5 class="card-title fw-semibold mb-4">Country</h5>
            <div class="table-responsive">
                <table id="table1" data-filter-control="true" data-filter-control-multiple-search="true"
                    data-filter-control-multiple-search-delimiter="," data-show-refresh="true"
                    data-show-pagination-switch="true" data-click-to-select="true" data-toggle="table"
                    data-url="{{route('request.countryData')}}" data-pagination="true" data-show-toggle="true"
                    data-show-export="true" data-show-columns="true" data-show-columns-toggle-all="true"
                    data-page-list="[10, 25, 50, 100,200]">
                    <thead>
                        <tr>
                            <th data-filter-control="input" data-field="SNO" data-formatter="operateSerial"
                                data-sortable="true">S.No.</th>
                            <th data-filter-control="input" data-field="name" data-sortable="true">Country Name</th>
                            <th data-filter-control="input" data-field="operate" data-formatter="operateEdit">
                                Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<br />
@if(session()->get('user')->roles[0]->name === "admin" || session()->get('user')->roles[0]->name === "snseaAdmin")
<div class="row">
    <div class="d-flex justify-content-center">
        <livewire:essential-modal-form-component wire:id="{{rand()}}" wire:key="{{rand()}}" modalId="add_city"
            tableId="table2" name="City" colorClass="primary" :className="$modelClass=App\Models\Cities::class"
            btnName="Add City" />
    </div>
</div>
@endif
<br />
<div class="row">
    <div class="card w-100">
        <div class="card-body p-4">
            <h5 class="card-title fw-semibold mb-4">City</h5>
            <div class="table-responsive">
                <table id="table2" data-filter-control="true" data-filter-control-multiple-search="true"
                    data-filter-control-multiple-search-delimiter="," data-virtual-scroll="true"
                    data-show-pagination-switch="true" data-click-to-select="true" data-toggle="table"
                    data-show-export="true" data-show-columns="true" data-show-columns-toggle-all="true"
                    data-url="{{route('request.cityData')}}" data-pagination="true" data-show-toggle="true"
                    data-show-refresh="true" data-flat="true" data-page-list="[10, 25, 50, 100,200]">
                    <thead>
                        <tr>
                            <th data-filter-control="input" data-field="SNO" data-formatter="operateSerial"
                                data-sortable="true">S.No.</th>
                            <th data-filter-control="input" data-field="name" data-sortable="true">City Name</th>
                            <th data-filter-control="input" data-field="operate" data-formatter="operateEdit">
                                Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@if(session()->get('user')->roles[0]->name === "admin" || session()->get('user')->roles[0]->name === "snseaAdmin")
<div class="row">
    <div class="d-flex justify-content-center">
        <livewire:essential-modal-form-component wire:id="{{rand()}}" wire:key="{{rand()}}" modalId="add_group"
            tableId="table3" name="Group" colorClass="indigo" :className="$modelClass=App\Models\Group::class"
            btnName="Add Group" />
    </div>
</div>
@endif
<br />
<div class="row">
    <div class="card w-100">
        <div class="card-body p-4">
            <h5 class="card-title fw-semibold mb-4">Group</h5>
            <div class="table-responsive">
                <table id="table3" data-filter-control="true" data-filter-control-multiple-search="true"
                    data-filter-control-multiple-search-delimiter="," data-virtual-scroll="true"
                    data-show-pagination-switch="true" data-click-to-select="true" data-toggle="table"
                    data-show-export="true" data-show-columns="true" data-show-columns-toggle-all="true"
                    data-url="{{route('request.groupData')}}" data-pagination="true" data-show-toggle="true"
                    data-show-refresh="true" data-flat="true" data-page-list="[10, 25, 50, 100,200]">
                    <thead>
                        <tr>
                            <th data-filter-control="input" data-field="SNO" data-formatter="operateSerial"
                                data-sortable="true">S.No.</th>
                            <th data-filter-control="input" data-field="name" data-sortable="true">Group Name</th>
                            <th data-filter-control="input" data-field="operate" data-formatter="operateEdit">
                                Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@if(session()->get('user')->roles[0]->name === "admin" || session()->get('user')->roles[0]->name === "snseaAdmin")
<div class="row">
    <div class="d-flex justify-content-center">
        <livewire:essential-modal-form-component wire:id="{{rand()}}" wire:key="{{rand()}}" modalId="add_staff_category"
            name="Staff Category" colorClass="info" :className="$modelClass=App\Models\StaffCategory::class"
            tableId="table7" btnName="Add Staff Category" />
    </div>
</div>
<br />
@endif
<div class="row">
    <div class="card w-100">
        <div class="card-body p-4">
            <h5 class="card-title fw-semibold mb-4">Staff Category</h5>
            <div class="table-responsive">
                <table id="table7" data-filter-control="true" data-filter-control-multiple-search="true"
                    data-filter-control-multiple-search-delimiter="," data-show-refresh="true"
                    data-show-pagination-switch="true" data-click-to-select="true" data-toggle="table"
                    data-url="{{route('api.staffCategory.index')}}" data-pagination="true" data-show-toggle="true"
                    data-show-export="true" data-show-columns="true" data-show-columns-toggle-all="true"
                    data-page-list="[10, 25, 50, 100,200]">
                    <thead>
                        <tr>
                            <th data-filter-control="input" data-field="SNO" data-formatter="operateSerial"
                                data-sortable="true">S.No.</th>
                            <th data-filter-control="input" data-field="name" data-sortable="true">Staff Category</th>
                            <th data-filter-control="input" data-field="operate" data-formatter="operateEdit">
                                Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<br />
@if(session()->get('user')->roles[0]->name === "admin" || session()->get('user')->roles[0]->name === "snseaAdmin")
<div class="row">
    <div class="d-flex justify-content-center">
        <a type="button" href="{{route('pages.addProgramPages')}}" class="btn btn-outline-dark">Add Program</a>
    </div>
</div>
<br />
@endif
<div class="row">
    <div class="card w-100">
        <div class="card-body p-4">
            <h5 class="card-title fw-semibold mb-4">Programs</h5>
            <div class="table-responsive">
                <table id="table4" data-filter-control="true" data-filter-control-multiple-search="true"
                    data-filter-control-multiple-search-delimiter="," data-virtual-scroll="true" data-flat="true"
                    data-page-list="[10, 25, 50, 100,200]" data-show-refresh="true" data-show-pagination-switch="true"
                    data-click-to-select="true" data-toggle="table" data-url="{{route('request.programsData')}}"
                    data-pagination="true" data-show-toggle="true" data-show-export="true" data-show-columns="true"
                    data-show-columns-toggle-all="true">
                    <thead>
                        <tr>
                            <th data-filter-control="input" data-field="SNO" data-formatter="operateSerial"
                                data-sortable="true">S.No.</th>
                            <th data-filter-control="input" data-field="program_name" data-sortable="true">Program Name
                            </th>
                            <th data-filter-control="input" data-field="program_day" data-sortable="true">Program Day
                            </th>
                            <th data-filter-control="input" data-field="program_start_time" data-sortable="true">Program
                                Start Time</th>
                            <th data-filter-control="input" data-field="program_end_time" data-sortable="true">Program
                                End Time</th>
                            <th data-filter-control="input" data-field="program_uid" data-sortable="true"
                                data-formatter="operateProgram">Actions</th>
                            {{-- <th data-filter-control="input" data-field="program_uid" data-sortable="true"
                                data-formatter="operateFormatter" data-events="operateProgram">Actions</th> --}}
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<br />
@if(session()->get('user')->roles[0]->name === "admin" || session()->get('user')->roles[0]->name === "snseaAdmin")
<div class="row">
    <div class="d-flex justify-content-center">
        <a type="button" href="{{route('pages.addCouponPages')}}" class="btn btn-outline-badar">Add Coupon</a>
    </div>
</div>
@endif
<br />
<div class="row">
    <div class="card w-100">
        <div class="card-body p-4">
            <h5 class="card-title fw-semibold mb-4">Coupon</h5>
            <div class="table-responsive">
                <table id="table5" data-filter-control="true" data-filter-control-multiple-search="true"
                    datInviteesa-filter-control-multiple-search-delimiter="," data-virtual-scroll="true"
                    data-show-pagination-switch="true" data-click-to-select="true" data-toggle="table"
                    data-show-export="true" data-show-columns="true" data-show-columns-toggle-all="true"
                    data-url="{{route('request.couponsData')}}" data-pagination="true" data-show-toggle="true"
                    data-filter-control-multiple-search-delimiter="," data-show-refresh="true" data-flat="true"
                    data-page-list="[10, 25, 50, 100,200]">
                    <thead>
                        <tr>
                            <th data-filter-control="input" data-field="SNO" data-formatter="operateSerial"
                                data-sortable="true">S.No.</th>
                            <th data-filter-control="input" data-field="coupon_name" data-sortable="true">Coupon Name
                            </th>
                            <th data-filter-control="input" data-field="coupon_day" data-sortable="true">Coupon Day</th>
                            <th data-filter-control="input" data-field="coupon_validity_start_time"
                                data-sortable="true">Coupon Validity Time</th>
                            <th data-filter-control="input" data-field="coupon_validity_end_time" data-sortable="true">
                                Coupon Validity Time</th>
                            <th data-filter-control="input" data-field="coupon_uid" data-sortable="true"
                                data-formatter="operateCoupon" >Actions
                            </th>
                            {{-- <th data-filter-control="input" data-field="coupon_uid" data-sortable="true"
                                data-formatter="operateFormatter" data-events="operateCoupons">Actions
                            </th> --}}
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@if(session()->get('user')->roles[0]->name === "admin" || session()->get('user')->roles[0]->name === "snseaAdmin")
<div class="row">
    <div class="d-flex justify-content-center">
        <a type="button" href="{{route('invitees.create')}}" class="btn btn-outline-danger">Add Invitees</a>
    </div>
</div>
@endif
<br />
<div class="row">
    <div class="card w-100">
        <div class="card-body p-4">
            <h5 class="card-title fw-semibold mb-4">Invitees</h5>
            <div class="table-responsive">
                <table id="table6" data-filter-control="true" data-filter-control-multiple-search="true"
                    data-filter-control-multiple-search-delimiter="," data-virtual-scroll="true"
                    data-show-pagination-switch="true" data-click-to-select="true" data-toggle="table"
                    data-show-export="true" data-show-columns="true" data-show-columns-toggle-all="true"
                    data-url="{{route('api.invitees.index')}}" data-pagination="true" data-show-toggle="true"
                    data-show-refresh="true" data-flat="true" data-page-list="[10, 25, 50, 100,200]">
                    <thead>
                        <tr>
                            <th data-filter-control="input" data-field="SNO" data-formatter="operateSerial"
                                data-sortable="true">S.No.</th>
                            <th data-filter-control="input" data-field="name" data-sortable="true">Invitees Name</th>
                            <th data-filter-control="input" data-field="rank.ranks_name" data-sortable="true">Invitees
                                Rank
                            </th>
                            <th data-filter-control="input" data-field="designation" data-sortable="true">Invitees
                                designation</th>
                            <th data-filter-control="input" data-field="uid" data-sortable="true"
                                data-formatter="operateInvitees">Actions
                            </th>
                            <!-- <th data-filter-control="input" data-field="uid" data-sortable="true"
                                data-formatter="operateFormatter" data-events="operateInvitees">Actions
                            </th> -->
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>


@include("layouts.tableFoot")
<script>
    // const $table1 = $('#table1'),
    //     $table2 = $('#table2'),
    //     $table3 = $('#table3'),
    //     $table4 = $('#table4'),
    //     $table5 = $('#table5'),
    //     $table6 = $('#table6'),
    //     $table7 = $('#table7');

    // window.operateCountry = {
    // [`click .remove`]: (e, value, row) => {
    // axios.post('{{route("request.deleteCountry")}}', {
    //         id:[row.id]
    //     }).then(
    //         function(response) {
    //             console.log(response)
    //             showDeleteToast(response.data.message)
    //         }).catch(function(error) {
    //             console.log(error);
    //         })
    // $table1.bootstrapTable('refresh');
    //     }
    // }

    window.operateCity = {
        [`click .remove`]: (e, value, row) => {
            axios.post('{{route("request.deleteCity")}}', {
                id: [row.id]
            }).then(
                function(response) {
                    console.log(response)
                    showDeleteToast(response.data.message)
                }).catch(function(error) {
                console.log(error);
            })
            // $table2.bootstrapTable('refresh');
        }
    }

    window.operateGroup = {
        [`click .remove`]: (e, value, row) => {
            axios.post('{{route("request.deleteGroup")}}', {
                id: value
            }).then(
                function(response) {
                    console.log(response)
                    showDeleteToast(response.data.message)
                }).catch(function(error) {
                console.log(error);
            })
            // $table3.bootstrapTable('refresh');
        }
    }


    // window.operateProgram = {
    //     [`click .remove`]: (e, value, row) => {
    //         axios.post('{{route("request.deleteProgram")}}', {
    //             id: value
    //         }).then(
    //             function(response) {
    //                 console.log(response)
    //                 showDeleteToast(response.data.message)
    //             }).catch(function(error) {
    //             console.log(error);
    //         })
    //         // $table4.bootstrapTable('refresh');
    //     }
    // }

    // window.operateCoupons = {
    //     [`click .remove`]: (e, value, row) => {
    //         axios.post('{{route("request.deleteCoupon")}}', {
    //             id: value
    //         }, {
    //             headers: {
    //                 'Accept': 'application/json'
    //             }
    //         }).then(
    //             function(response) {
    //                 console.log(response)
    //                 showDeleteToast(response.data.message)
    //             }).catch(function(error) {
    //             console.log(error);
    //         })
    //         // $table5.bootstrapTable('refresh');
    //     }
    // }

    // window.operateInvitees = {
    //     [`click .remove`]: (e, value, row) => {
    //         let route = '{{route("api.invitees.destroy", ":id")}}'.replace(':id', row.uid);
    //         axios.delete(route, {
    //             id: value
    //         }, {
    //             headers: {
    //                 'Accept': 'application/json'
    //             }
    //         }).then(
    //             function(response) {
    //                 console.log(response)
    //                 showDeleteToast(response.data.message)
    //             }).catch(function(error) {
    //             console.log(error);
    //         })
    //         // $table5.bootstrapTable('refresh');
    //     }
    // }

    window.operateStaffCat = {
        [`click .remove`]: (e, value, row) => {
            console.log(row)
            let route = '{{route("api.staffCategory.destroy", ":id")}}'.replace(':id', row.id);
            axios.delete(route, {
                id: value
            }, {
                headers: {
                    'Accept': 'application/json'
                }
            }).then(
                function(response) {
                    console.log(response)
                    showDeleteToast(response.data.message)
                }).catch(function(error) {
                console.log(error);
            })
            // $table6.bootstrapTable('refresh');
        }
    }

    function refreshTable(tableId){
        $(`#${tableId}`).bootstrapTable('refresh');
    }

    function operateSerial(value, row, index) {
        return index + 1;
    }


        // Axios Request For EDIT Start
        const editRequest = (event, route,tableId) => {
            event.preventDefault();
            console.log(tableId);
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
        // axios.post(route, formValues, {
        //         headers: {
        //             'Accept': 'application/json'
        //         }
        //     })
        axios({
            method:tableId == 'table7'?'put':'post',
            url: route,
            data: formValues,
            headers: {
                    'Accept': 'application/json'
                }
        }).then(response => {
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
                refreshTable(tableId);
            });
    }
    // Axios Request For EDIT End


    // Modal for editing Start 
    function openEditModal(id, name, e) {
        let route = '';
        let tableId = e.closest('table').id;

        switch (tableId) {
            case 'table1':
                route = '{{route("request.updateCountry", ":id")}}'.replace(':id', id);
                break;
            case 'table2':
                route = '{{route("request.updateCity", ":id")}}'.replace(':id', id);
                break;
            case 'table3':
                route = '{{route("request.updateGroup", ":id")}}'.replace(':id', id);
                break;
            case 'table7':
                route = '{{route("api.staffCategory.update", ":id")}}'.replace(':id', id);
                break;
            default:
                break;
        }

        document.getElementById('edit-form').setAttribute('onsubmit', `editRequest(event, '${route}','${tableId}')`);
        // console.log(e.closest('table').id);
        // Set values
        document.getElementById('name').value = name;

        // Show modal
        const editModal = new bootstrap.Modal(document.getElementById('edit-modal'));
        editModal.show();
    }
    // Modal for editing End 

    


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


    function operateCities(value, row, index) {
        if (value) {
            return `<div class="left"><button class="btn btn-badar" onclick="deleteAction('${path}',${value},${index},'#table1')"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg></button></div>`;
        }
    }



    function operateProgram(value, row, index) {
        route = '{{route("pages.addProgramPages", ":id")}}'.replace(':id', value);
        return [
            `<a href="${route}" class="btn btn-outline-success"><i class="ti ti-edit" style="font-size:22px; widht:24px; height:24px;"></i></a>`
        ]
    }
    function operateCoupon(value, row, index) {
        route = '{{route("pages.addCouponPages", ":id")}}'.replace(':id', value);
        return [
            `<a href="${route}" class="btn btn-outline-success"><i class="ti ti-edit" style="font-size:22px; widht:24px; height:24px;"></i></a>`
        ]
    }

    function operateEdit(value, row, index) {
        // console.log(row);
        return [
            `<button type="button" class="btn btn-outline-success" onclick="openEditModal('${row.id}','${row.name}',this)"><i class="ti ti-edit" style="font-size:22px; widht:24px; height:24px;"></i></button>`
        ]
    }
    function operateInvitees(value, row, index) {
        route = '{{route("invitees.edit", ":id")}}'.replace(':id', value);
        return [
            `<a href="${route}" class="btn btn-outline-success"><i class="ti ti-edit" style="font-size:22px; widht:24px; height:24px;"></i></a>`
        ]
    }


    // function operatePlans(value, row, index) {
    //     if (value) {
    //         // return [
    //         return `<div class="left"><form name="deleteProgram" action="{{route('request.deleteProgram')}}" method="POST"><input type="hidden" name="programId" value="${value}"/><button class="btn btn-badar" type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg></button></form></div>`;
    //         // ].join(``)
    //     }
    // }

    // function operateCoupons(value, row, index) {
    //     if (value) {
    //         // return [
    //         return `<div class="left"><form name="deleteCoupon" action="{{route('request.deleteCoupon')}}" method="POST"><input type="hidden" name="couponId" value="${value}"/><button class="btn btn-badar" type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg></button></form></div>`;
    //         // ].join(``)
    //     }
    // }
</script>
<script>
    $(document).ready(function () {
    $('#table1').bootstrapTable('refresh');
  });
</script>

@endsection
@endauth