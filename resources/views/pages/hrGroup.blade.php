@auth
@extends('layouts.layout')
@section("content")
@if (session('error'))
<script>
    alert("{{session('error')}}");
</script>
@endif
<style>
    body {
        font-family: Arial;
    }

    /* Style the tab */
    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
    }

    .active {
        background-color: var(--bs-success);
        font-weight: bold;
    }

    .rejected {
        background-color: var(--bs-badar);
        font-weight: bold;
        color: black;
    }

    .approved {
        background-color: var(--bs-success);
        font-weight: bold;
        color: white;
    }

    /* Style the buttons inside the tab */
    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 17px;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
        background-color: #ddd;
    }

    /* Create an active/current tablink class */
    .tab button.active {
        background-color: #ccc;
    }

    /* Style the tab content */
    .tabcontent {
        display: none;
        padding: 0px 12px;
        border: 1px solid #ccc;
        border-top: none;
    }
</style>


<div class="row">
    <div class="card w-100">
        <div class="card-body p-4">
            @if(session('user')->roles[0]->name === "admin" || session()->get('user')->roles[0]->name === "bxssUser" ||
            session()->get('user')->roles[0]->name == "authority" ||session()->get('user')->roles[0]->name == "sender")
            <div class="row">
                <div class="d-flex flex-wrap">
                    @if(session()->get('user')->roles[0]->name != "authority"&&session()->get('user')->roles[0]->name !=
                    "printer")
                    <button id="sent" class="status-action-button btn btn-danger mb-2">Sent For
                        Approval</button>&nbsp;
                    <button id="pending" class="status-action-button btn btn-warning mb-2">Status Pending</button>&nbsp;
                    @endif
                    @if(session()->get('user')->roles[0]->name != "sender" && session()->get('user')->roles[0]->name !=
                    "printer")
                    <button id="approved" class="status-action-button btn btn-success mb-2">Approved</button>&nbsp;
                    <button id="rejected" class="status-action-button btn btn-badar mb-2">Rejected</button>
                    @endif
                </div>
            </div>
            <br />
            @endif
            {{-- <div class="row">
                <div class="d-flex col-md-4 col-sm-2">
                </div>
                <div class="d-flex col-md-4 col-sm-10">
                    <div class="card overflow-hidden">
                        <div class="card-body p-4">
                            <h5 class="card-title text-center mb-9 fw-semibold">Functionary Pass Limit</h5>
                            <h4 class="d-flex justify-content-center mb-9 fw-semibold">
                                {{$functionaryStaffLimit?->hr_quantity}}</h4>
                            <div class="align-items-center">
                                <div class="d-flex justify-content-center">
                                    <p>
                                        You have <b>{{$functionaryStaffRemaing}}</b> Remaining Functionary Pass(es) Left
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="row">
                @if(session('user')->roles[0]->name === "admin" || session()->get('user')->roles[0]->name ==="bxssUser"
                || session()->get('user')->roles[0]->name === "hrRep"|| session()->get('user')->roles[0]->name ===
                "printer" ||session()->get('user')->roles[0]->name == "sender")
                <div class="d-flex flex-wrap">
                    @if($functionaryStaffRemaing>0 && session()->get('user')->roles[0]->name != "printer"
                    &&session()->get('user')->roles[0]->name != "authority")
                    <a type="button" href="{{route('pages.addHrGroupStaffRender',$id)}}"
                        class="btn btn-primary mb-2">Add
                        HR Staff</a>&nbsp;
                    @endif
                    @if(session('user')->roles[0]->name === "admin" || session()->get('user')->roles[0]->name
                    ==="bxssUser"|| session()->get('user')->roles[0]->name === "printer")
                    <button class="print-action-button btn btn-primary mb-2">Print Badge</button>&nbsp;
                    <button class="print-action-button-with-pic btn btn-success mb-2">Print Badge With
                        Picture</button>&nbsp;
                    <button type="button" class="print-a4-badge-button btn btn-info mb-2">Print A4 Badge</button>&nbsp;
                    @endif
                </div>
                @endif
            </div>
            <div class="table-responsive text-capitalize">
                <table id="table" data-filter-control-multiple-search="true" data-header-style="headerStyle"
                    data-filter-control-multiple-search-delimiter="," data-click-to-select="true" data-show-print="true"
                    data-virtual-scroll="true" data-filter-control="true" data-pagination="true" data-show-export="true"
                    data-show-columns="true" data-show-refresh="true" data-show-pagination-switch="true"
                    data-row-style="rowStyle" data-page-list="[10, 25, 50, 100]" data-reorderable-columns="true"
                    data-print-as-filtered-and-sorted-on-ui="true" data-url="{{route('request.getHrGroupStaff',$id)}}">
                    <thead>

                        <tr>
                            <th data-field="state" data-checkbox="true" data-print-ignore="true"></th>
                            <th data-filter-control="input" data-field="SNO" data-formatter="operateSerial"><b>S.No.</b>
                            </th>
                            <th data-filter-control="input" data-formatter="operateBadge" data-force-hide="true"
                                data-print-ignore="true">Badge
                                Print</th>
                            {{-- <th data-filter-control="input" data-field="hr_type" data-sortable="true"
                                data-formatter="operateText" data-force-hide="true">Pass Type</th> --}}
                            <th data-filter-control="input" data-field="hr_security_status" data-sortable="true"
                                data-formatter="operateText" data-force-hide="true" data-print-ignore="true">Security
                                Status</th>
                            <th data-filter-control="input" data-field="hr_first_name" data-sortable="true"
                                data-fixed-columns="true" data-formatter="operateFirstAndLastName">Name</th>
                            <th data-filter-control="input" data-field="hr_father_name" data-sortable="true"
                                data-fixed-columns="true" data-formatter="operateText">Father/Husband Name</th>
                            <th data-filter-control="input" data-field="hrName.hr_name" data-sortable="true"
                                data-fixed-columns="true" data-formatter="operateText">Company Name</th>
                            <th data-filter-control="input" data-field="hr_designation" data-sortable="true"
                                data-fixed-columns="true" data-formatter="operateText">Designation </th>
                            <th data-filter-control="input" data-field="hr_department" data-sortable="true"
                                data-fixed-columns="true" data-formatter="operateText" data-force-hide="true"
                                data-print-ignore="true">Department
                            </th>
                            <th data-filter-control="input" data-field="hr_address" data-sortable="true"
                                data-formatter="operateText">Home Address</th>
                            <th data-filter-control="input" data-field="hr_city" data-sortable="true"
                                data-formatter="operateText" data-force-hide="true" data-print-ignore="true">City</th>
                            <th data-filter-control="input" data-field="hr_country" data-sortable="true"
                                data-formatter="operateText" data-force-hide="true" data-print-ignore="true">Country
                            </th>
                            <th data-filter-control="input" data-field="hr_job_type" data-sortable="true"
                                data-formatter="operateText" data-force-hide="true" data-print-ignore="true">Job Type
                            </th>
                            <th data-filter-control="input" data-field="hr_nationality" data-sortable="true"
                                data-formatter="operateText" data-force-hide="true" data-print-ignore="true">Nationality
                            </th>
                            <th data-filter-control="input" data-field="hr_identity" data-sortable="true"
                                data-formatter="operateDigits">CNIC/Passport</th>
                            <th data-filter-control="input" data-field="hr_identity_expiry" data-sortable="true"
                                data-formatter="operateText" data-force-hide="true" data-print-ignore="true">Identity
                                Expiry</th>
                            <th data-filter-control="input" data-field="hr_contact" data-sortable="true"
                                data-formatter="operateDigits">Contact</th>
                            <th data-filter-control="input" data-field="hr_dob" data-sortable="true"
                                data-formatter="operateText" data-force-hide="true" data-print-ignore="true">DOB</th>
                            <th data-filter-control="input" data-field="employee_type" data-sortable="true"
                                data-formatter="operateText" data-force-hide="true" data-print-ignore="true">Employee
                                Type</th>
                            <th data-filter-control="input" data-field="isPrinted" data-sortable="true"
                                data-formatter="operateDigits" data-force-hide="true" data-print-ignore="true">Printed
                            </th>
                            <th data-field="pictureUrl" data-formatter="operatepicture">Picture</th>
                            {{-- <th data-field="cnicfront.img_blob" data-width="250" data-width-unit="px"
                                data-formatter="operatecnic" data-force-hide="true">
                                CNIC front</th>
                            <th data-field="cnicback.img_blob" data-width="250" data-width-unit="px"
                                data-formatter="operatecnic" data-force-hide="true">
                                CNIC back</th> --}}
                            <th data-filter-control="input" data-field="hr_remarks" data-sortable="true"
                                data-formatter="operateText" data-force-hide="true" data-print-ignore="true">Remarks
                            </th>
                            <th data-filter-control="input" data-field="created_at" data-sortable="true"
                                data-force-hide="true" data-formatter="operateDate" data-print-ignore="true">Created At
                            </th>
                            <th data-filter-control="input" data-field="updated_at" data-sortable="true"
                                data-force-hide="true" data-formatter="operateDate" data-print-ignore="true">Last
                                Updated
                            </th>
                            @if(session()->get('user')->roles[0]->name === "admin" ||
                            session()->get('user')->roles[0]->name === "bxssUser"||
                            session()->get('user')->roles[0]->name == 'sender')
                            <th data-field="uid" data-formatter="operateEdit" data-force-hide="true"
                                data-force-hide="true" data-print-ignore="true">Edit</th>
                            <th data-field="uid" data-formatter="operateDelete" data-force-hide="true"
                                data-force-hide="true" data-print-ignore="true">Delete</th>
                            @endif
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="hrA4BadgeOptionsModal" tabindex="-1" aria-labelledby="hrA4BadgeOptionsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hrA4BadgeOptionsModalLabel">Print A4 Badge</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="hrA4BadgeEvent" class="form-label">Event</label>
                    <select id="hrA4BadgeEvent" class="form-select">
                        <option value="">Select event</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}">{{ $event->display_name ?: $event->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="hrA4BadgeType" class="form-label">Category</label>
                    <select id="hrA4BadgeType" class="form-select">
                        <option value="">Select category</option>
                        <option value="0">Trade Visitor</option>
                        <option value="1">Exhibitor</option>
                        <option value="2">Event Manager</option>
                        <option value="3">Organizer</option>
                        <option value="4">Guest Of Honour</option>
                        <option value="5">Panelist</option>
                    </select>
                </div>
                <div class="alert alert-danger d-none" id="hrA4BadgeOptionsError"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="openHrA4BadgePrint">Open Badges</button>
            </div>
        </div>
    </div>
</div>
@include("layouts.tableFoot")
<script>
    function operateText(value, row, index) {
        return value ? value : "N/A"
    }

    function operateDigits(value, row, index) {
        return value ? value : 0
    }

    function operateDate(value, row, index) {
        return value ? value.slice(0, 10) : "N/A"
    }

    function operateFirstAndLastName(value, row, index) {
        return `${row.hr_first_name} ${row.hr_last_name}`;
    }

    function statusChangerFormatter(value, row, index) {
        if (value) {
            return [
                '<div class="left">',
                '<a class="btn btn-danger" href="statusChanger/' + row.uid + '">',
                '<span><i class="ti ti-users" style="font-size:24px;"></i></span>',
                '</a>',
                '</div>',
            ].join('')
        } else {
            return [
                '-',
            ].join('')
        }
    }

    function statusFormatter(value, row, index) {
        if (value != null) {
            return value ? ['<div class="left">', 'Yes', '</div>'].join('') : ['<div class="left">', 'No', '</div>'].join('');
        }
    }

    function operatepicture(value, row, index) {
        if (value != null) {
            return value ? `<img  onerror="this.style.display='none'" style="object-fit: cover;" width="100" height="120" src="${value}"  />` : ['<div class="left">', 'Not Available', '</div>'].join('');
        }
    }

    // function operatepicture(value, row, index) {
    //     if (value != null) {
    //         return value ? `<img  onerror="this.style.display='none'" style="object-fit: cover;" width="100" height="120" src=${value} />` : ['<div class="left">', 'Not Available', '</div>'].join('');
    //     }
    // }

    function operatecnic(value, row, index) {
        if (value != null) {
            return value ? `<img  onerror="this.style.display='none'" style="object-fit: cover;" width="150px" height="100px" src=${value} />` : ['<div class="left">', 'Not Available', '</div>'].join('');
        }
    }

    function operateEdit(value, row, index) {
        if (row.hr_security_status == 'pending') {
            return [
                '<div class="left">',
                '<a class="btn btn-success" href="' + row.hr_uid + '/addHrStaffRender/' + value + '">',
                '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">',
                '<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>',
                '<path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>',
                '<path d="M6 21v-2a4 4 0 0 1 4 -4h3.5"></path>',
                '<path d="M18.42 15.61a2.1 2.1 0 0 1 2.97 2.97l-3.39 3.42h-3v-3l3.42 -3.39z"></path>',
                '</svg>',
                '</a>',
                '</div>'
            ].join('')
        }
    }

    function operateBadge(value, row, index) {
        if (row.hr_security_status == "approved" && row.hr_type == "Functionary") {
            return [
                '<div class="left">',
                '<a class="btn btn-primary" href="' + row.hr_uid + '/addHrStaffRender/' + value + '">',
                '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-id-badge-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 12h3v4h-3z" /><path d="M10 6h-6a1 1 0 0 0 -1 1v12a1 1 0 0 0 1 1h16a1 1 0 0 0 1 -1v-12a1 1 0 0 0 -1 -1h-6" /><path d="M10 3m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v3a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z" /><path d="M14 16h2" /><path d="M14 12h4" /></svg>',
                '</a>',
                '</div>'
            ].join('')
        } else if (row.hr_type == "Temporary") {
            return [
                '<div class="left">',
                '<a class="btn btn-primary" href="' + row.company_uid + '/addHrGroupStaff/' + value + '">',
                '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-id-badge-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 12h3v4h-3z" /><path d="M10 6h-6a1 1 0 0 0 -1 1v12a1 1 0 0 0 1 1h16a1 1 0 0 0 1 -1v-12a1 1 0 0 0 -1 -1h-6" /><path d="M10 3m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v3a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z" /><path d="M14 16h2" /><path d="M14 12h4" /></svg>',
                '</a>',
                '</div>'
            ].join('')
        }
    }

    function operateSelf(value, row, index) {
        if (value != null) {
            return !value ? 'Rep' : 'Self';
        }
    }

    function operateSerial(value, row, index) {
        return index + 1;
    }

    function headerStyle(column) {

    }



    function operateYesNo(value, row, index) {
        return value == 1 ? 'Yes' : 'No';
    }

    function rowStyle(row) {
        if (row.isPrinted == 1) {
            return {
                classes: 'approved'
            }
        }
        if (row.hr_security_status == 'rejected') {
            return {
                classes: 'rejected'
            }
        } else if (row.hr_security_status == 'approved') {
            return {
                classes: 'approved'
            }
        } else {
            return {
                classes: ''
            }
        }
        if (row.functionaryPending != 0) {
            return {
                classes: 'pending'
            }
        }
    }

    function operateDelete(value, row, index) {
        if (row.hr_security_status == 'pending') {
            // console.log(row.uid);
            let params = `'${row.hr_uid}','${value}'`;
            return [
                '<div class="left">',
                '<button class="btn btn-badar" onclick="deleteStaff(' + params + ')">',
                '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user-minus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4c.348 0 .686 .045 1.009 .128" /><path d="M16 19h6" /></svg>',
                '</button>',
                '</div>'
            ].join('')
        }
    }

    var $mytable = $('#table');

    function deleteStaff(id, staffId) {
        if (!confirm('Are you sure?')) return;

        axios.post(`/hrGroup/${id}/deleteHrStaffRequest/${staffId}`)
            .then(function(response) {
                if (response.data.success) {
                    alert(response.data.message);
                    $mytable.bootstrapTable('refresh');
                } else {
                    alert('Delete failed: ' + response.data.message);
                }
            })
            .catch(function(error) {
                alert('Error occurred while deleting.');
                console.error(error);
            });
    }


    ['#table'].map((val => {
        var $table = $(val)
        var selectedRow = {}
        var $button = $('.status-action-button')
        var $printButton = $('.print-action-button')
        var $printButtonWithPic = $('.print-action-button-with-pic')
        var $printA4BadgeButton = $('.print-a4-badge-button')
        var selectedHrA4Identities = ''
        var hrA4BadgeOptionsModalElement = document.getElementById('hrA4BadgeOptionsModal')
        var hrA4BadgeOptionsModal = hrA4BadgeOptionsModalElement ? new bootstrap.Modal(hrA4BadgeOptionsModalElement) : null

        function showHrA4BadgeOptionsError(message) {
            $('#hrA4BadgeOptionsError').removeClass('d-none').text(message)
        }

        function getSelectedHrRows() {
            let rows = $table.bootstrapTable('getSelections')

            if (!rows.length) {
                rows = $table.bootstrapTable('getData').filter((row) => row.state)
            }

            return rows
        }

        $(function() {
            $button.click(function(val) {
                let uidArray = []
                $table.bootstrapTable('getSelections').map((val) => {
                    uidArray.push(val.uid);
                })
                axios.post("{{route('request.updateHrGroupStaffSecurityStatus')}}", {
                    uidArray,
                    status: val.target.id
                }).then(
                    function(response) {
                        $table.bootstrapTable('refresh');
                    }).catch(function(error) {
                    console.log(error);
                })
            })
            $(function() {
                $printButton.click(function(val) {
                    let uidArray = []
                    $table.bootstrapTable('getSelections').map((val) => {
                        uidArray.push(val.id);
                    })
                    // console.log(uidArray);

                    uidArray.length ? window.location.href = "{{  url('') }}/badge/hr/" + uidArray + "" : alert("Please atleast select one");
                })
            })
            $(function() {
                $printButtonWithPic.click(function(val) {
                    let uidArray = []
                    $table.bootstrapTable('getSelections').map((val) => {
                        uidArray.push(val.id);
                    })
                    uidArray.length ? window.location.href = "{{  url('') }}/badge/hr/" + uidArray + "/true" : alert("Please atleast select one");
                })
            })
            $(function() {
                $printA4BadgeButton.click(function(val) {
                    selectedHrA4Identities = getSelectedHrRows()
                        .map((val) => val.hr_identity || val.id)
                        .filter(Boolean)
                        .map((identity) => encodeURIComponent(identity))
                        .join(',')

                    if (!selectedHrA4Identities) {
                        alert("Please atleast select one")
                        return
                    }

                    $('#hrA4BadgeOptionsError').addClass('d-none').text('')
                    $('#hrA4BadgeEvent').val('')
                    $('#hrA4BadgeType').val('')
                    hrA4BadgeOptionsModal?.show()
                })
            })
            $(function() {
                $('#openHrA4BadgePrint').click(function() {
                    let eventId = $('#hrA4BadgeEvent').val()
                    let category = $('#hrA4BadgeType').val()

                    if (!eventId) {
                        showHrA4BadgeOptionsError('Please select an event.')
                        return
                    }

                    if (category === '') {
                        showHrA4BadgeOptionsError('Please select a category.')
                        return
                    }

                    if (!selectedHrA4Identities) {
                        showHrA4BadgeOptionsError('Please select at least one row.')
                        return
                    }

                    hrA4BadgeOptionsModal?.hide()
                    window.open(`{{ url('') }}/a4EBadge/${encodeURIComponent(eventId)}/${encodeURIComponent(category)}/${selectedHrA4Identities}`, '_blank')
                })
            })
        })
        $(val).bootstrapTable({
            exportTypes: ['json', 'csv', 'txt', 'sql', 'excel', 'pdf'],
            exportOptions: {
                fileName: '{{$hrName->hr_name}}',
                type: 'pdf',
                jspdf: {
                    orientation: 'l',
                    autotable: {
                        styles: {
                            rowHeight: 60,
                            overflow: 'linebreak',
                            valign: 'middle',
                        },
                        headerStyles: {
                            fontSize: 12,
                            fontStyle: 'bold',
                        },
                        tableWidth: 'auto',
                    },
                }
            }
        })
    }))
</script>
@endsection
@endauth
