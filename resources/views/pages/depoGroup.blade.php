@auth
@extends('layouts.layout')
@section("content")
@if (session('error'))
<script>
    alert("{{session('error')}}");
</script>
@endif
@if (session('success'))
<script>
    alert("{{ session('success') }}");
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
        background-color: var(--bs-primary);
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
            {{-- <div class="row">
                <div class="d-flex flex-wrap">
                    if(session('user')->roles[0]->name === "admin")
                    <button id="sent" class="status-action-button btn btn-danger mb-2">Sent For
                        Approval</button>&nbsp;
                    <button id="pending" class="status-action-button btn btn-warning mb-2">Status Pending</button>&nbsp;
                    <button id="approved" class="status-action-button btn btn-success mb-2">Approved</button>&nbsp;
                    <button id="rejected" class="status-action-button btn btn-badar mb-2">Rejected</button>
                    endif
                </div>
            </div> --}}
            <br />
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Import failed:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            <div class="row">
                @if(session('user')->roles[0]->name === "admin" || session()->get('user')->roles[0]->name
                =="bxssUser"|| session('user')->roles[0]->name == "depo" || session('user')->roles[0]->name ==
                "depoRep" || session()->get('user')->roles[0]->name =="printer")
                <div class="d-flex flex-wrap">
                    @if(session('user')->roles[0]->name === "admin" || session()->get('user')->roles[0]->name
                    ==="bxssUser"|| session('user')->roles[0]->name === "depo" || session('user')->roles[0]->name ===
                    "depoRep"|| session()->get('user')->roles[0]->name=="printer")
                    @if($depoGuestRemaing>0)
                    <a type="button" href="{{route('pages.addDepoGuestRender',$id)}}" class="btn btn-primary mb-2">Add
                        Staff</a>&nbsp;
                    @endif
                    @endif
                    @if(session('user')->roles[0]->name === "admin" || session('user')->roles[0]->name === "depo" ||
                    session()->get('user')->roles[0]->name ==="bxssUser" ||
                    session()->get('user')->roles[0]->name=="printer")
                    <button class="print-action-button btn btn-primary mb-2">Print Badge</button>&nbsp;
                    <button class="print-action-button-envelope btn btn-warning mb-2">Print Envelope</button>&nbsp;
                    <button class="print-action-button-tag btn btn-warning mb-2">Print Tag</button>&nbsp;
                    <button class="print-action-button-with-pic btn btn-success mb-2">Print Badge With
                        Picture</button>&nbsp;
                    <button type="button" class="print-a4-badge-button btn btn-info mb-2">Print A4 Badge</button>&nbsp;
                    <a type="button" href="{{ asset('downloadable/reference_import_sheet.xlsx') }}" download>
                        <button type="button" class="btn btn-primary">Reference Import Sheet</button>
                    </a>
                    @endif
                    @if(session('user')->roles[0]->name === "admin")
                    <form action="{{ route('import.depoGuest') }}" style="display:inline;" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="file" style="display:inline; width:50%;" class="form-control" name="file"
                            accept=".xls,.xlsx,.csv" required>
                        <button type="submit" style="display:inline;" class="btn btn-success">Import</button>
                    </form>
                    <div>
                        <select class="form-select selected-theme" style="width:fit-content; display:inline;">
                            <option value="">Select Template for Printing</option>
                            @foreach(\App\Models\Template::all() as $template)
                            <option value="{{ $template->id }}">
                                {{ $template->name }} ({{ $template->type }})
                            </option>
                            @endforeach
                        </select>
                        <button type="button" style="display:inline;" class="btn btn-success print-with-theme">Print With Template</button>
                    </div>
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
                    data-print-as-filtered-and-sorted-on-ui="true" data-url="{{route('request.getDepoGuest',$id)}}">
                    <thead>
                        <tr>
                            <th data-field="state" data-checkbox="true" data-print-ignore="true"></th>
                            <!-- <th data-filter-control="input" data-field="SNO" data-formatter="operateSerial"
                                data-print-ignore><b>S.No.</b> -->
                            </th>
                            <th data-filter-control="input" data-field="depo_guest_service" data-sortable="true" data-formatter="operateText">Service</th>
                            <th data-filter-control="input" data-field="rank.ranks_name" data-formatter="operateText"
                                data-force-hide="true" data-print-ignore="true">
                                Rank</th>
                            <th data-filter-control="input" data-field="depo_guest_name" data-sortable="true"
                                data-formatter="operateText">Name</th>
                            <th data-filter-control="input" data-field="depoName.depo_rep_name" data-sortable="true"
                                data-formatter="operateText">Company Name</th>
                            <th data-filter-control="input" data-field="depo_guest_designation" data-sortable="true"
                                data-formatter="operateText">Designation</th>
                            <th data-filter-control="input" data-field="depo_address" data-sortable="true"
                                data-formatter="operateText" data-force-hide="true" data-print-ignore="true">Home Address</th>
                            <th data-filter-control="input" data-field="depo_identity" data-sortable="true"
                                data-formatter="operateText" data-print-ignore>CNIC/Passport</th>
                            <th data-filter-control="input" data-field="depo_guest_contact" data-sortable="true"
                                data-formatter="operateDigits" data-print-ignore="true">Contact</th>
                            <th data-filter-control="input" data-field="depo_guest_email" data-sortable="true"
                                data-formatter="operateText" data-force-hide="true" data-print-ignore="true">Email</th>
                            <th data-filter-control="input" data-field="depoName.category.name" data-sortable="true"
                                data-formatter="operateText">Badge Category</th>
                            <th data-filter-control="input" data-field="isPrinted" data-sortable="true"
                                data-formatter="operateDigits" data-force-hide="true" data-print-ignore="true">Printed
                            </th>
                            <th data-filter-control="input" data-field="created_at" data-sortable="true"
                                data-force-hide="true" data-formatter="operateDate" data-print-ignore="true">Created At
                            </th>
                            <th data-filter-control="input" data-field="updated_at" data-sortable="true"
                                data-force-hide="true" data-formatter="operateDate" data-print-ignore="true">Last Updated
                            </th>
                            {{-- <th data-filter-control="input" data-field="picture" data-sortable="true"
                                data-formatter="operateBool">Image Uploaded</th> --}}
                            <th data-field="pictureUrl" data-formatter="operatepicture" data-print-ignore="true">Picture</th>
                            @if(session('user')->roles[0]->name !== "attandeeUser" ||
                            session()->get('user')->roles[0]->name
                            !=="web_user" || session()->get('user')->roles[0]->name != "printer"
                            ||session()->get('user')->roles[0]->name != "authority")
                            <th data-field="uid" data-formatter="operateEdit" data-print-ignore="true" data-force-hide="true"
                                data-force-hide="true">Edit</th>
                            @if(session('user')->roles[0]->name !="depoRep")
                            <th data-field="uid" data-formatter="operateDelete" data-force-hide="true"
                                data-force-hide="true" data-print-ignore="true">Delete</th>
                            @endif
                            @endif
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="depoA4BadgeOptionsModal" tabindex="-1" aria-labelledby="depoA4BadgeOptionsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="depoA4BadgeOptionsModalLabel">Print A4 Badge</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="depoA4BadgeEvent" class="form-label">Event</label>
                    <select id="depoA4BadgeEvent" class="form-select">
                        <option value="">Select event</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}">{{ $event->display_name ?: $event->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="depoA4BadgeType" class="form-label">Category</label>
                    <select id="depoA4BadgeType" class="form-select">
                        <option value="">Select category</option>
                        <option value="0">Trade Visitor</option>
                        <option value="1">Exhibitor</option>
                        <option value="2">Event Manager</option>
                        <option value="3">Organizer</option>
                        <option value="4">Guest Of Honour</option>
                        <option value="5">Panelist</option>
                    </select>
                </div>
                <div class="alert alert-danger d-none" id="depoA4BadgeOptionsError"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="openDepoA4BadgePrint">Open Badges</button>
            </div>
        </div>
    </div>
</div>
@include("layouts.tableFoot")
<script>
    const user = @json(session()->get('user'));
    // console.log(user)

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
        return `${row.depo_name}`;
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

    // function operatepicture(value, row, index) {
    //     if (value != null) {
    //         return value ? `<img  onerror="this.style.display='none'" style="object-fit: cover;" width="100" height="120" src=${value} />` : ['<div class="left">', 'Not Available', '</div>'].join('');
    //     }
    // }

    function operatepicture(value, row, index) {
        if (value != null) {
            return value ? `<img  onerror="this.style.display='none'" style="object-fit: cover;" width="100" height="120" src="${value}" />` : ['<div class="left">', 'Not Available', '</div>'].join('');
        }
    }

    function operatecnic(value, row, index) {
        if (value != null) {
            return value ? `<img  onerror="this.style.display='none'" style="object-fit: cover;" width="150px" height="100px" src=${value} />` : ['<div class="left">', 'Not Available', '</div>'].join('');
        }
    }

    // function operateBool(value, row, index){
    //     return value.img_blob?'Yes':'No';
    // }

    function operateEdit(value, row, index) {
        if (row.depo_uid && user.roles[0].name === "admin" || row.isPrinted == 0) {
            return [
                '<div class="left">',
                '<a class="btn btn-success" href="' + row.depo_uid + '/addDepoGuestRender/' + value + '">',
                '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">',
                '<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>',
                '<path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>',
                '<path d="M6 21v-2a4 4 0 0 1 4 -4h3.5"></path>',
                '<path d="M18.42 15.61a2.1 2.1 0 0 1 2.97 2.97l-3.39 3.42h-3v-3l3.42 -3.39z"></path>',
                '</svg>',
                '</a>',
                '</div>'
            ].join('')
        } else {
            return [
                '<div class="left">',
                '<a class="btn btn-primary" href="#" title="User Locked">',
                '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user-hexagon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 13a3 3 0 1 0 0 -6a3 3 0 0 0 0 6z" /><path d="M6.201 18.744a4 4 0 0 1 3.799 -2.744h4a4 4 0 0 1 3.798 2.741" /><path d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" /></svg>',
                '</a>',
                '</div>'
            ].join('')
        }
    }

    function operateBadge(value, row, index) {
        if (row.depo_security_status == "approved") {
            return [
                '<div class="left">',
                '<a class="btn btn-primary" href="' + row.depo_uid + '/addDepoGuestRender/' + value + '">',
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

    function headerStyle(column) {}

    function operateYesNo(value, row, index) {
        return value == 1 ? 'Yes' : 'No';
    }

    function rowStyle(row) {
        if (row.depo_security_status == 'rejected') {
            return {
                classes: 'rejected'
            }
        } else if (row.depo_security_status == 'approved') {
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
        if (row.uid) {
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
        var $printEnvelope = $('.print-action-button-envelope')
        var $printTag = $('.print-action-button-tag')
        var $printButtonWithPic = $('.print-action-button-with-pic')
        var $printA4BadgeButton = $('.print-a4-badge-button')
        var $selectedTheme = $('.selected-theme')
        var $printWithTheme = $('.print-with-theme')
        var selectedDepoA4Identities = ''
        var depoA4BadgeOptionsModalElement = document.getElementById('depoA4BadgeOptionsModal')
        var depoA4BadgeOptionsModal = depoA4BadgeOptionsModalElement ? new bootstrap.Modal(depoA4BadgeOptionsModalElement) : null

        function showDepoA4BadgeOptionsError(message) {
            $('#depoA4BadgeOptionsError').removeClass('d-none').text(message)
        }

        function getSelectedDepoRows() {
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
                    uidArray.push(val.id);
                })
            })
        })
        $(function() {
            $printButton.click(function(val) {
                let uidArray = []
                $table.bootstrapTable('getSelections').map((val) => {
                    uidArray.push(val.id);
                })
                // console.log(uidArray)
                uidArray.length ? window.location.href = "{{  url('') }}/badge/depo/" + uidArray + "" : alert("Please atleast select one");
            })
        })
        $(function() {
            $printButtonWithPic.click(function(val) {
                let uidArray = []
                $table.bootstrapTable('getSelections').map((val) => {
                    uidArray.push(val.id);
                })
                // console.log(uidArray)
                uidArray.length ? window.location.href = "{{  url('') }}/badge/depo/" + uidArray + "/true" : alert("Please atleast select one");
            })
        })
        $(function() {
            $printA4BadgeButton.click(function(val) {
                selectedDepoA4Identities = getSelectedDepoRows()
                    .map((val) => val.depo_identity || val.id)
                    .filter(Boolean)
                    .map((identity) => encodeURIComponent(identity))
                    .join(',')

                if (!selectedDepoA4Identities) {
                    alert("Please atleast select one")
                    return
                }

                $('#depoA4BadgeOptionsError').addClass('d-none').text('')
                $('#depoA4BadgeEvent').val('')
                $('#depoA4BadgeType').val('')
                depoA4BadgeOptionsModal?.show()
            })
        })
        $(function() {
            $('#openDepoA4BadgePrint').click(function() {
                let eventId = $('#depoA4BadgeEvent').val()
                let category = $('#depoA4BadgeType').val()

                if (!eventId) {
                    showDepoA4BadgeOptionsError('Please select an event.')
                    return
                }

                if (category === '') {
                    showDepoA4BadgeOptionsError('Please select a category.')
                    return
                }

                if (!selectedDepoA4Identities) {
                    showDepoA4BadgeOptionsError('Please select at least one row.')
                    return
                }

                depoA4BadgeOptionsModal?.hide()
                window.open(`{{ url('') }}/a4EBadge/${encodeURIComponent(eventId)}/${encodeURIComponent(category)}/${selectedDepoA4Identities}`, '_blank')
            })
        })
        $(function() {
            $printEnvelope.click(function(val) {
                let uidArray = []
                $table.bootstrapTable('getSelections').map((val) => {
                    uidArray.push(val.id);
                })
                // console.log(uidArray);
                uidArray.length ? window.location.href = "{{  url('') }}/printEnvelope/depo/" + uidArray : alert("Please atleast select one");
            })
        })
        $(function() {
            $printTag.click(function(val) {
                let uidArray = []
                $table.bootstrapTable('getSelections').map((val) => {
                    uidArray.push(val.id);
                })
                uidArray.length ? window.location.href = "{{  url('') }}/printTag/depo/" + uidArray : alert("Please atleast select one");
            })
        })

        $(function() {
            $printWithTheme.click(function(val) {
                let uidArray = []
                let templateId = $selectedTheme.val();
                $table.bootstrapTable('getSelections').map((val) => {
                    uidArray.push(val.id);
                })
                if (!templateId) {
                    alert("Please select a template for printing.");
                    return;
                }
                // console.log(uidArray, templateId)
                uidArray.length ? window.location.href = "{{  url('') }}/printWithTemplate/" + templateId + "/" + uidArray : alert("Please atleast select one");
            })
        })
        $(val).bootstrapTable({
            exportTypes: ['json', 'csv', 'txt', 'sql', 'excel', 'pdf'],
            exportOptions: {
                fileName: '{{isset($depo)?$depo->depo_rep_name:"table download"}}',
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
