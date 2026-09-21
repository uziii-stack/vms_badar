<?php

namespace App\Livewire;

use App\Models\DepoGroup;
use App\Models\DepoGuest;
use App\Models\HrGroup;
use App\Models\HrStaff;
use App\Models\MediaGroup;
use App\Models\MediaStaff;
use App\Models\Organization;
use App\Models\OrganizationStaff;
use App\Models\Visitors;
use Livewire\Component;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Illuminate\Database\QueryException;

#[Lazy]
class SearchAttandeeComponent extends Component
{
    public $prefixValueToBeSearch = '';
    public string $valueToBeSearch = '';
    public $attandees = [];
    public $results = [];



    // For Searched
    public function search()
    {


        if (strlen($this->valueToBeSearch) > 8) {
            $visitorExist = Visitors::where('code', $this->valueToBeSearch)->orWhere('identity', $this->valueToBeSearch)->first();
            if ($visitorExist) {
                $this->attandees = $visitorExist;
            } else {
                if (ctype_alpha(substr($this->valueToBeSearch, 0, 2))) {
                    $this->prefixValueToBeSearch = substr($this->valueToBeSearch, 0, 2);
                } else {
                    $this->prefixValueToBeSearch = 'External';
                }
                switch ($this->prefixValueToBeSearch) {
                    case 'HR':
                    case 'DP':
                        $this->prefixValueToBeSearch = '';
                        $visitorCreated = [];
                        try {
                            $depoGuest = DepoGuest::where('badge_type', $this->valueToBeSearch)->first();
                            $depoGroup = $depoGuest ? DepoGroup::where('uid', $depoGuest->depo_uid)->first() : [];
                            $visitorCreated = $depoGuest ? Visitors::create([
                                'uid' => $depoGuest['uid'],
                                'name' => $depoGuest['depo_guest_name'],
                                'event' => 'Pakistan',
                                'event' => 'INDUS-AI',
                                'attandeeCompany' => $depoGroup->depo_category,
                                'designation' => $depoGuest['depo_guest_designation'],
                                'identity' => $depoGuest['depo_identity'],
                                'contact' => $depoGuest['depo_guest_contact'],
                                'code' => $depoGuest['badge_type']
                            ]) : false;
                        } catch (QueryException $exception) {
                            if ($exception->errorInfo[2]) {
                                session()->flash('error', 'Data not found, SomeThing Went Wrong!' . $exception->errorInfo[2]);
                            } else {
                                session()->flash('error', 'Data not updated, SomeThing Went Wrong!' . $exception->errorInfo);
                            }
                        }
                        $this->attandees = Visitors::where('code', $this->valueToBeSearch)->first();
                        break;
                    case 'EM':
                    case 'VL':
                        $this->prefixValueToBeSearch = '';
                        $visitorCreated = [];
                        try {
                            $hrstaff = HrStaff::where('code', $this->valueToBeSearch)->first();
                            $hrGroup = $hrstaff ? HrGroup::where('uid', $hrstaff->hr_uid)->first() : [];
                            $visitorCreated = $hrstaff ? Visitors::create([
                                'uid' => $hrstaff['uid'],
                                'name' => $hrstaff['hr_first_name'] . ' ' . $hrstaff['hr_last_name'],
                                'attandeeCompany' => $hrGroup->hr_name,
                                'attandeeCountry' => $hrstaff['hr_nationality'],
                                'event' => 'INDUS-AI',
                                'designation' => $hrstaff['hr_designation'],
                                'identity' => $hrstaff['hr_identity'],
                                'contact' => $hrstaff['hr_contact'],
                                'code' => $hrstaff['code'],
                            ]) : false;
                        } catch (QueryException $exception) {
                            if ($exception->errorInfo[2]) {
                                session()->flash('error', 'Visitor not updated, SomeThing Went Wrong!');
                                return $exception->errorInfo[2];
                            } else {
                                session()->flash('error', 'Visitor not updated, SomeThing Went Wrong!' . $exception->errorInfo[2]);
                            }
                        }
                        $this->attandees = $visitorCreated ? Visitors::where('code', $this->valueToBeSearch)->first() : [];
                        break;
                    case 'FN':
                    case 'TP':
                        $this->prefixValueToBeSearch = '';
                        $visitorCreated = [];
                        try {
                            $orgstaff = OrganizationStaff::where('code', $this->valueToBeSearch)->first();
                            $companyName = $orgstaff ? Organization::where('uid', $orgstaff->company_uid)->first() : [];
                            $visitorCreated = $orgstaff ? Visitors::create([
                                'uid' => $orgstaff['uid'],
                                'name' => $orgstaff['staff_first_name'] . ' ' . $orgstaff['staff_last_name'],
                                'attandeeCompany' => $companyName->company_name,
                                'attandeeCountry' => $orgstaff['staff_nationality'],
                                'event' => 'INDUS-AI',
                                'designation' => $orgstaff['staff_designation'],
                                'identity' => $orgstaff['staff_identity'],
                                'contact' => $orgstaff['staff_contact'],
                                'code' => $orgstaff['code'],
                            ]) : false;
                        } catch (QueryException $exception) {
                            if ($exception->errorInfo[2]) {
                                session()->flash('error', 'Visitor not updated, SomeThing Went Wrong!');
                                return $exception->errorInfo[2];
                            } else {
                                session()->flash('error', 'Visitor not updated, SomeThing Went Wrong!' . $exception->errorInfo[2]);
                            }
                        }
                        $this->attandees = $visitorCreated ? Visitors::where('code', $this->valueToBeSearch)->first() : [];
                        break;
                    case 'ME':
                        $this->prefixValueToBeSearch = '';
                        $mediastaff = MediaStaff::where('code', $this->valueToBeSearch)->first();
                        $mediaCompany = $mediastaff ? MediaGroup::where('uid', $mediastaff->media_uid)->first() : [];
                        $visitorCreated = [];
                        try {
                            $visitorCreated = $mediastaff ? Visitors::create([
                                'uid' => $mediastaff['uid'],
                                'name' => $mediastaff['media_staff_first_name'] . ' ' . $mediastaff['media_staff_last_name'],
                                'attandeeCompany' => $mediaCompany->media_name,
                                'attandeeCountry' => $mediastaff['media_staff_nationality'],
                                'event' => 'INDUS-AI',
                                'designation' => $mediastaff['media_staff_designation'],
                                'identity' => $mediastaff['media_staff_identity'],
                                'contact' => $mediastaff['media_staff_contact'],
                                'code' => $mediastaff['code'],
                            ]) : false;
                        } catch (QueryException $exception) {
                            if ($exception->errorInfo[2]) {
                                session()->flash('error', 'Visitor not updated, SomeThing Went Wrong!');
                                return $exception->errorInfo[2];
                            } else {
                                session()->flash('error', 'Visitor not updated, SomeThing Went Wrong!' . $exception->errorInfo[2]);
                            }
                        }
                        $this->attandees = $visitorCreated ? Visitors::where('code', $this->valueToBeSearch)->first() : [];
                        break;
                    case 'External':
                        $this->prefixValueToBeSearch = '';
                        $depoReq = DepoGuest::where('depo_identity', $this->valueToBeSearch)->first();
                        $depoGroup = $depoReq ? DepoGroup::where('uid', $depoReq->depo_uid)->first() : [];
                        $hrStaffReq = HrStaff::where('hr_identity', $this->valueToBeSearch)->first();
                        $hrGroup = $hrStaffReq ? HrGroup::where('uid', $hrStaffReq->hr_uid)->first() : [];
                        $orgstaffReq = OrganizationStaff::where('staff_identity', $this->valueToBeSearch)->first();
                        $companyName = $orgstaffReq ? Organization::where('uid', $orgstaffReq->company_uid)->first() : [];
                        $mediastaffReq = MediaStaff::where('media_staff_identity', $this->valueToBeSearch)->first();
                        $mediaCompany = $mediastaffReq ? MediaGroup::where('uid', $mediastaffReq->media_uid)->first() : [];
                        $visitorCreated = [];
                        if ($depoReq) {
                            try {
                                $visitorCreated = $depoReq ? Visitors::create([
                                    'uid' => $depoReq['uid'],
                                    'name' => $depoReq['depo_guest_name'],
                                    'attandeeCompany' => $depoGroup ? $depoGroup->depo_category : [],
                                    'attandeeCountry' => $depoGroup ? $depoGroup->depo_category : 'Pakistan',
                                    'designation' => $depoReq['depo_guest_designation'],
                                    'event' => 'INDUS-AI',
                                    'identity' => $depoReq['depo_identity'],
                                    'contact' => $depoReq['depo_guest_contact'],
                                    'code' => $depoReq['badge_type']
                                ]) : false;
                            } catch (QueryException $exception) {
                                if ($exception->errorInfo[2]) {
                                    session()->flash('error', 'Data not found, SomeThing Went Wrong!'.$exception->errorInfo[2]);
                                } else {
                                    session()->flash('error', 'Data not updated, SomeThing Went Wrong!' . $exception->errorInfo[2]);
                                }
                            }
                            $this->attandees = $depoReq;
                        }
                        if ($hrStaffReq) {
                            try {
                                $visitorCreated = $hrStaffReq ? Visitors::create([
                                    'uid' => $hrStaffReq['uid'],
                                    'name' => $hrStaffReq['hr_first_name'] . ' ' . $hrStaffReq['hr_last_name'],
                                    'attandeeCompany' => $hrGroup ? $hrGroup->hr_name : [],
                                    'attandeeCountry' => $hrStaffReq['hr_nationality'],
                                    'event' => 'INDUS-AI',
                                    'designation' => $hrStaffReq['hr_designation'],
                                    'identity' => $hrStaffReq['hr_identity'],
                                    'contact' => $hrStaffReq['hr_contact'],
                                    'code' => $hrStaffReq['code'],
                                ]) : false;
                            } catch (QueryException $exception) {
                                if ($exception->errorInfo[2]) {
                                    session()->flash('error', 'Visitor not updated, SomeThing Went Wrong!');
                                    return $exception->errorInfo[2];
                                } else {
                                    session()->flash('error', 'Visitor not updated, SomeThing Went Wrong!' . $exception->errorInfo[2]);
                                }
                            }
                        }
                        if ($orgstaffReq) {
                            try {
                                $visitorCreated = $orgstaffReq ? Visitors::create([
                                    'uid' => $orgstaffReq['uid'],
                                    'name' => $orgstaffReq['staff_first_name'] . ' ' . $orgstaffReq['staff_last_name'],
                                    'attandeeCompany' => $companyName ? $companyName->company_name : [],
                                    'attandeeCountry' => $orgstaffReq['staff_nationality'],
                                    'event' => 'INDUS-AI',
                                    'designation' => $orgstaffReq['staff_designation'],
                                    'identity' => $orgstaffReq['staff_identity'],
                                    'contact' => $orgstaffReq['staff_contact'],
                                    'code' => $orgstaffReq['code'],
                                ]) : false;
                            } catch (QueryException $exception) {
                                if ($exception->errorInfo[2]) {
                                    session()->flash('error', 'Visitor not updated, SomeThing Went Wrong!');
                                    return $exception->errorInfo[2];
                                } else {
                                    session()->flash('error', 'Visitor not updated, SomeThing Went Wrong!' . $exception->errorInfo[2]);
                                }
                            }
                        }
                        if ($mediastaffReq) {
                            try {
                                $visitorCreated = $mediastaffReq ? Visitors::create([
                                    'uid' => $mediastaffReq['uid'],
                                    'name' => $mediastaffReq['media_staff_first_name'] . ' ' . $mediastaffReq['media_staff_last_name'],
                                    'attandeeCompany' => $mediaCompany ? $mediaCompany?->media_name : null,
                                    'attandeeCountry' => $mediastaffReq['media_staff_nationality'],
                                    'event' => 'INDUS-AI',
                                    'designation' => $mediastaffReq['media_staff_designation'],
                                    'identity' => $mediastaffReq['media_staff_identity'],
                                    'contact' => $mediastaffReq['media_staff_contact'],
                                    'code' => $mediastaffReq['code'],
                                ]) : [];
                            } catch (QueryException $exception) {
                                if ($exception->errorInfo[2]) {
                                    session()->flash('error', 'Visitor not updated, SomeThing Went Wrong!');
                                    return $exception->errorInfo[2];
                                } else {
                                    session()->flash('error', 'Visitor not updated, SomeThing Went Wrong!' . $exception->errorInfo[2]);
                                }
                            }
                        }
                        $this->attandees = $visitorCreated ? Visitors::where('code', $this->valueToBeSearch)->orWhere('identity', $this->valueToBeSearch)->first() : [];
                        // $this->attandees = (object)array(['name' => 'External Api']);
                        break;
                    default:
                        $this->prefixValueToBeSearch = '';
                        $this->attandees = [];
                        break;
                }
            }

            if (isset($this->attandees['uid'])) {
                $this->dispatch('searchUpdate', $this->attandees['uid'])->to(MarkAttendanceComponent::class);
                $this->dispatch('searchAttandeeUpdate', $this->attandees['uid'])->to(AddAttandeeComponent::class);
                $this->dispatch('userUpdate', $this->attandees)->to(AttandeeListComponent::class);
            } else {
                $this->dispatch('searchUpdate', false)->to(MarkAttendanceComponent::class);
                $this->dispatch('searchAttandeeUpdate', '')->to(AddAttandeeComponent::class);
            }
            $this->valueToBeSearch = '';
            $this->dispatch('localRefresh')->self();
        } else {

            session()->flash('error', 'Please put 10 - 12 Alpha numeric Code.');
            $this->valueToBeSearch = '';
        }

        // if (substr($this->valueToBeSearch, 0, 4) == 'TVFA') {
        //     $this->prefixValueToBeSearch = substr($this->valueToBeSearch, 0, 4);
        // } elseif (ctype_alpha(substr($this->valueToBeSearch, 0, 2))) {
        //     $this->prefixValueToBeSearch = substr($this->valueToBeSearch, 0, 2);
        // } else {
        //     $this->prefixValueToBeSearch = 'External';
        // }
        // switch ($this->prefixValueToBeSearch) {
        //     case 'TVFA':
        //         $this->prefixValueToBeSearch = '';
        //         $this->attandees = Visitors::where('code', $this->valueToBeSearch)->first();
        //         break;
        //     case 'HR':
        //         $this->prefixValueToBeSearch = '';
        //         $depoGuest = DepoGuest::where('badge_type', $this->valueToBeSearch)->first();
        //         $isExist = Visitors::where('code', $depoGuest['badge_type'])->first();
        //         if (!$isExist) {
        //             $visitorCreated = Visitors::create([
        //                 'uid' => $depoGuest['uid'],
        //                 'name' => $depoGuest['depo_guest_name'],
        //                 'designation' => $depoGuest['depo_guest_designation'],
        //                 'identity' => $depoGuest['depo_identity'],
        //                 'code' => $depoGuest['badge_type'],
        //             ]);
        //             $this->attandees = $visitorCreated ? Visitors::where('code', $depoGuest['badge_type'])->first() : [];
        //         }
        //         break;
        //     case 'EM':
        //     case 'VL':
        //         $this->prefixValueToBeSearch = '';
        //         $hrstaff = HrStaff::where('code', $this->valueToBeSearch)->first();
        //         $isExist = Visitors::where('code', $hrstaff['badge_type'])->first();
        //         if (!$isExist) {
        //             $visitorCreated = Visitors::create([
        //                 'uid' => $hrstaff['uid'],
        //                 'name' => $hrstaff['hr_first_name'] . $hrstaff['hr_last_name'],
        //                 'designation' => $hrstaff['hr_designation'],

        //                 'identity' => $hrstaff['hr_identity'],
        //                 'code' => $hrstaff['code'],
        //             ]);
        //             $this->attandees = $visitorCreated ? Visitors::where('code', $hrstaff['badge_type'])->first() : [];
        //         } else {
        //             $this->attandees = Visitors::where('code', $hrstaff['badge_type'])->first();
        //         }
        //         break;
        //     case 'External':
        //         $this->prefixValueToBeSearch = '';
        //         $this->attandees = (object)array(['name' => 'External Api']);
        //         break;
        //     default:
        //         $this->prefixValueToBeSearch = '';
        //         $this->attandees = (object)array();
        //         break;
        // }
        // $this->dispatch('userUpdate', $this->attandees)->to(AttandeeListComponent::class);
    }

    #[On('localRefresh')]
    public function render()
    {
        return view('livewire.search-attandee-component');
    }
}
