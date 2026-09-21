@auth
@extends('layouts.layout')
@section("content")
<livewire:add-user-component type="temporary" />
<span id="alert-comp"></span>
@if(session()->get('user')->roles[0]->name === "admin" || session()->get('user')->roles[0]->name === "temporaryPass")
<div class="modal fade" id="outputModal" tabindex="-1">
</div>

<div class="row">
    <div class="card w-100">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="table" data-filter-control-multiple-search="true"
                    data-filter-control-multiple-search-delimiter="," data-virtual-scroll="true"
                    data-filter-control="true" data-toggle="table" data-flat="true" data-pagination="true"
                    data-show-toggle="true" data-show-export="true" data-show-columns="true" data-show-refresh="true"
                    data-show-pagination-switch="true" data-show-columns-toggle-all="true" data-row-style="rowStyle"
                    data-page-list="[10, 25, 50, 100]" data-url="{{route('request.temporaryPass')}}">
                    <thead>
                        <tr>
                            <th data-filter-control="input" data-field="SNO" data-formatter="operateSerial">S.No.</th>
                            <th data-filter-control="input" data-field="name" data-sortable="true"
                                data-formatter="operateText">Name</th>
                            <th data-filter-control="input" data-field="identity" data-sortable="true"
                                data-formatter="operateText">Identity</th>
                            <th data-filter-control="input" data-field="organisation" data-sortable="true"
                                data-formatter="operateText">Organisation</th>
                            <th data-filter-control="input" data-field="code" data-sortable="true"
                                data-formatter="operateText">Code</th>
                            <th data-filter-control="input" data-field="status" data-sortable="true"
                                data-formatter="statusFormatter">Active</th>
                                <th data-field="id" data-formatter="operateSlip">Slip</th>
                            <th data-field="id" data-formatter="operateEdit">Edit</th>
                            {{-- <th data-field="id" data-formatter="operateDelete">Delete</th> --}}
                            <th data-filter-control="input" data-field="created_at" data-sortable="true"
                                data-formatter="operateDate">Created At</th>
                            <th data-filter-control="input" data-field="updated_at" data-sortable="true"
                                data-formatter="operateDate">Last Updated
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@include("layouts.tableFoot")
<script>
    const $table = $('#table');
    const modalContainer = document.getElementById('outputModal');

    function operateSerial(value, row, index) {
        return index + 1;
    }

    function operateText(value, row, index) {
        return value ? value : "N/A"
    }

    function statusFormatter(value, row, index) {
        if (value != null) {
            return value ? ['<div class="left">', 'Yes', '</div>'].join('') : ['<div class="left">', 'No', '</div>'].join('');
        }
    }

    function operateDate(value, row, index) {
        return value ? value.slice(0, 10) : "N/A"
    }

    function operateEdit(value, row, index) {
        return [`<button id="btn-3" type="button" class="btn btn-outline-success mb-1 mx-1"
            onclick='generateModalComponent(this,"editModal","${value}","${row.name}","${row.identity}","${row.organisation}","${row.status}")'>
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path><path d="M6 21v-2a4 4 0 0 1 4 -4h3.5"></path><path d="M18.42 15.61a2.1 2.1 0 0 1 2.97 2.97l-3.39 3.42h-3v-3l3.42 -3.39z"></path></svg></button>`];
    }

    function operateSlip(value, row, index) {
        return [`<button id="btn-3" type="button" class="btn btn-outline-success mb-1 mx-1"
            onclick='generateSlip("${value}")'>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-receipt"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 2a3 3 0 0 1 3 3v16a1 1 0 0 1 -1.555 .832l-2.318 -1.545l-1.42 1.42a1 1 0 0 1 -1.32 .083l-.094 -.083l-1.293 -1.292l-1.293 1.292a1 1 0 0 1 -1.32 .083l-.094 -.083l-1.421 -1.42l-2.317 1.545l-.019 .012l-.054 .03l-.028 .017l-.054 .023l-.05 .023l-.049 .015l-.06 .019l-.052 .009l-.057 .011l-.084 .006l-.026 .003h-.022l-.049 -.003h-.039l-.013 -.003h-.016l-.041 -.008l-.038 -.005l-.015 -.005l-.018 -.002l-.034 -.011l-.04 -.01l-.019 -.007l-.015 -.004l-.029 -.013l-.04 -.015l-.021 -.011l-.013 -.005l-.028 -.016l-.036 -.018l-.014 -.01l-.018 -.01l-.038 -.027l-.022 -.014l-.01 -.009l-.02 -.014l-.045 -.041l-.012 -.008l-.024 -.024l-.035 -.039l-.02 -.02l-.007 -.011l-.011 -.012l-.032 -.045l-.02 -.025l-.012 -.019l-.03 -.054l-.017 -.028l-.023 -.054l-.023 -.05a1 1 0 0 1 -.034 -.108l-.01 -.057l-.01 -.053l-.009 -.132v-16a3 3 0 0 1 3 -3zm-2 12h-2a1 1 0 0 0 0 2h2a1 1 0 0 0 0 -2m0 -4h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0 -2m0 -4h-6a1 1 0 1 0 0 2h6a1 1 0 0 0 0 -2" /></svg>`];
    }
    
    function generateSlip(id){
        window.open(`{{url('')}}/temporarySlip/${id}`, "_blank", "width=500,height=500");
    }

    function operateDelete(value, row, index) {
        if (value) {
            // console.log(row.uid);
            return [
                '<div class="left">',
                '<button class="btn btn-badar" onclick="deleteStaff(' + value + ')">',
                '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user-minus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4c.348 0 .686 .045 1.009 .128" /><path d="M16 19h6" /></svg>',
                '</button>',
                '</div>'
            ].join('')
        }
    }
        

    function deleteStaff(id) {
        if (!confirm('Are you sure?')) return;
        let route = '{{route("temporaryPass.destroy", ":id")}}'.replace(':id', id);
        axios.delete(route)
            .then(function(response) {
                if (response.data.success) {
                    alert(response.data.message);
                } else {
                    alert('Delete failed: ' + response.data.message);
                }
            })
            .catch(function(error) {
                alert('Error occurred while deleting.');
                console.error(error);
            }).finally(() => {
                $table.bootstrapTable('uncheckAll');
                $table.bootstrapTable('refresh');
                console.log('Request processing completed.');
            });
    }


    function generateModalComponent(e, modalId,rowId = '',name,identity,organisation,status) {
        e.disabled = true;
        let route = '{{route("request.editTemporaryPass", ":id")}}'.replace(':id', rowId);
        const modalHtml = `<div class="modal-dialog">
        <div class="modal-content" id="${modalId}">
            <form id="${modalId}_form" name="${modalId}_form"
             method="POST" onsubmit="sendingPostRequest(event,'${modalId}','${route}')">
                <div class="modal-header">
                    <h5 class="modal-title">Temporary Pass Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                        <div class="row">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" aria-describedby="textHelp" id="name" name="name" value="${name}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="identity" class="form-label">Identity</label>
                                    <input type="text" class="form-control" aria-describedby="textHelp" id="identity" name="identity" value="${identity}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="organisation" class="form-label">Organisation</label>
                                    <input type="text" class="form-control" aria-describedby="textHelp" id="organisation" name="organisation" value="${organisation}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="1" ${status == 1?'selected':''}>Active</option>
                                        <option value="0" ${status == 0?'selected':''}>Inactive</option>
                                    </select>
                                </div>
                        </div>
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

    const sendingPostRequest = (event, modalId, route) => {
        event.preventDefault();
        const formData = new FormData(event.target);
        const formValues ={};
        // Process each entry in FormData
        formData.forEach((value, key) => {
                // If the key does not exist, simply add it
                formValues[key] = value;
        });

        // const lengthOfForm = Object.keys(formValues).length; // Length Of Values getting from from 
        // console.log(formValues);
        axios.post(route, formValues)
            .then(response => {
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
                $table.bootstrapTable('refresh');
                console.log('Request processing completed.');
            });
    }
</script>
@endif

@endsection
@endauth