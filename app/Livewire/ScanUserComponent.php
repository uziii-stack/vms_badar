<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\{DepoGroup, DepoGuest, HrGroup, HrStaff, MediaGroup, MediaStaff, Organization, OrganizationStaff, Visitors};

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

// class ScanUserComponent extends Component
// {
//     public string $modalBody = '';
//     public string $valueToBeSearch = '';
//     public string $dataScan = '';

//     public $results;


//     public function search()
//     {
//         $valueToBeSearchFilter = preg_replace('/[^A-Za-z0-9]/', '', $this->valueToBeSearch);
//         $visitorReq = Visitors::where('identity', $valueToBeSearchFilter)->orWhere('code', $valueToBeSearchFilter)->first();

//         $depoReq = DepoGuest::where('depo_identity', $valueToBeSearchFilter)->orWhere('badge_type', $valueToBeSearchFilter)->select([
//             'depo_guest_name as name',   // rename first_name → name
//             'depo_guest_designation as designation',
//             'depo_identity as identity',
//             'depo_guest_email as contact',
//             'badge_type as code',
//             'depo_uid',
//         ])->first();

//         $hrStaffReq = HrStaff::where('hr_identity', $valueToBeSearchFilter)->orWhere('code', $valueToBeSearchFilter)->select([
//             'hr_first_name as name',
//             'hr_last_name as another_name',
//             'hr_designation as designation',
//             'hr_identity as identity',
//             'hr_contact as contact',
//             'hr_uid',
//             'code',
//         ])->first();
//         $orgstaffReq = OrganizationStaff::where('staff_identity', $valueToBeSearchFilter)->orWhere('code', $valueToBeSearchFilter)->select([
//             'staff_first_name as name',
//             'staff_last_name as another_name',
//             'staff_designation as designation',
//             'staff_identity as identity',
//             'staff_contact as contact',
//             'company_uid',
//             'code',
//         ])->first();
//         $mediastaffReq = MediaStaff::where('media_staff_identity', $valueToBeSearchFilter)->orWhere('code', $valueToBeSearchFilter)->select([
//             'media_staff_first_name as name',
//             'media_staff_last_name as another_name',
//             'media_staff_designation as designation',
//             'media_staff_identity as identity',
//             'media_staff_contact as contact',
//             'media_uid',
//             'code',
//         ])->first();

//         if ($visitorReq) {
//             $this->results = '<p>'
//                 . e($visitorReq['name'] ?? '') . ' '
//                 . e($visitorReq['designation'] ?? '') . '<br/>'
//                 . e($visitorReq['designation'] ?? '') . '<br/>'
//                 . e($visitorReq['identity'] ?? '') . '<br/>'
//                 . e($visitorReq['company'] ?? '') . '<br/>'
//                 . e($visitorReq['code'] ?? '') . '<br/>Verified</p>';;
//         };

//         if ($depoReq) {
//             $this->results = $depoReq;
//             $depoReq['groupDetail'] = DepoGroup::where('uid', $depoReq->depo_uid)->select([
//                 'depo_rep_name as companyName',
//             ])->first();
//         };


//         if ($hrStaffReq) {
//             $this->results = $hrStaffReq;
//             $hrStaffReq['groupDetail'] =  HrGroup::where('uid', $hrStaffReq->hr_uid)->select([
//                 'hr_name as companyName',
//             ])->first();
//         }

//         if ($orgstaffReq) {
//             $this->results = $orgstaffReq;
//             $orgstaffReq['groupDetail'] = Organization::where('uid', $orgstaffReq->company_uid)->select([
//                 'company_name as companyName',
//             ])->first();
//         }

//         if ($mediastaffReq) {
//             $this->results = $mediastaffReq;
//             $mediastaffReq['groupDetail'] = MediaGroup::where('uid', $mediastaffReq->media_uid)->select([
//                 'media_name as companyName',
//             ])->first();
//         }


//         $valueToBeSearchFilter = '';
//         if (!empty($this->results)) {
//             $this->modalBody = '<p>'
//                 . e($this->results['name'] ?? '') . ' '
//                 . e($this->results['another_name'] ?? '') . '<br/>'
//                 . e($this->results['designation'] ?? '') . '<br/>'
//                 . e($this->results['identity'] ?? '') . '<br/>'
//                 . e($this->results['groupDetail']['companyName'] ?? '') . '<br/>'
//                 . e($this->results['code'] ?? '') . '<br/>Verified</p>';

//             $this->dispatch('alertRefresh', $this->results)->self();
//         } else {
//             $this->modalBody = 'No Data Found';
//             $this->dispatch('alertRefresh', 'No Data Found')->self();
//         }
//         $this->results = [];
//     }

//     public function render()
//     {
//         return view('livewire.scan-user-component');
//     }
// }

class ScanUserComponent extends Component
{
    public string $modalBody = '';
    public string $valueToBeSearch = '';
    public string $dataScan = '';
    public array $results = [];

    public function search(): void
    {
        $this->modalBody = '';
        $this->results = [];

        try {
            $valueToBeSearchFilter = trim(preg_replace('/[^A-Za-z0-9]/', '', $this->valueToBeSearch));

            if (empty($valueToBeSearchFilter)) {
                $this->modalBody = 'Please enter a valid ID or code.';
                $this->dispatch('alertRefresh', $this->modalBody)->self();
                return;
            }

            // Try each data source in order of priority
            $this->results = $this->searchInTables($valueToBeSearchFilter);

            if (!empty($this->results)) {
                $this->modalBody = $this->formatResult($this->results);
                $this->dispatch('alertRefresh', $this->results)->self();
            } else {
                $this->modalBody = 'No Data Found';
                $this->dispatch('alertRefresh', $this->modalBody)->self();
            }
        } catch (ModelNotFoundException $e) {
            $this->modalBody = 'No matching record found.';
            Log::warning('Scan search model not found: ' . $e->getMessage());
        } catch (Exception $e) {
            $this->modalBody = 'An unexpected error occurred. Please try again.';
            Log::error('ScanUserComponent error: ' . $e->getMessage());
        }
    }

    /**
     * Search in all relevant tables and return the first valid result
     */
    private function searchInTables(string $filter): array
    {
        // Visitors
        $visitor = Visitors::with('eventSessions')
            ->where('identity', $filter)
            ->orWhere('code', $filter)
            ->first();

        if ($visitor) {
            return [
                'name' => $visitor->name ?? '',
                'designation' => $visitor->designation ?? '',
                'identity' => $visitor->identity ?? '',
                'companyName' => $visitor->company ?? '',
                'code' => $visitor->code ?? '',
                'sessions' => $visitor->eventSessions
                    ->pluck('title')
                    ->filter()
                    ->values()
                    ->all(),
            ];
        }

        // Depo Guest
        $depo = DepoGuest::where('depo_identity', $filter)
            ->orWhere('badge_type', $filter)
            ->select([
                'depo_guest_name as name',
                'depo_guest_designation as designation',
                'depo_identity as identity',
                'depo_guest_email as contact',
                'badge_type as code',
                'depo_uid',
            ])
            ->first();

        if ($depo) {
            $group = DepoGroup::where('uid', $depo->depo_uid)
                ->select('depo_rep_name as companyName')
                ->first();
            return array_merge($depo->toArray(), [
                'companyName' => $group->companyName ?? '',
            ]);
        }

        // HR Staff
        $hr = HrStaff::where('hr_identity', $filter)
            ->orWhere('code', $filter)
            ->select([
                'hr_first_name as name',
                'hr_last_name as another_name',
                'hr_designation as designation',
                'hr_identity as identity',
                'hr_contact as contact',
                'hr_uid',
                'code',
            ])
            ->first();

        if ($hr) {
            $group = HrGroup::where('uid', $hr->hr_uid)
                ->select('hr_name as companyName')
                ->first();
            return array_merge($hr->toArray(), [
                'companyName' => $group->companyName ?? '',
            ]);
        }

        // Organization Staff
        $org = OrganizationStaff::where('staff_identity', $filter)
            ->orWhere('code', $filter)
            ->select([
                'staff_first_name as name',
                'staff_last_name as another_name',
                'staff_designation as designation',
                'staff_identity as identity',
                'staff_contact as contact',
                'company_uid',
                'code',
            ])
            ->first();

        if ($org) {
            $group = Organization::where('uid', $org->company_uid)
                ->select('company_name as companyName')
                ->first();
            return array_merge($org->toArray(), [
                'companyName' => $group->companyName ?? '',
            ]);
        }

        // Media Staff
        $media = MediaStaff::where('media_staff_identity', $filter)
            ->orWhere('code', $filter)
            ->select([
                'media_staff_first_name as name',
                'media_staff_last_name as another_name',
                'media_staff_designation as designation',
                'media_staff_identity as identity',
                'media_staff_contact as contact',
                'media_uid',
                'code',
            ])
            ->first();

        if ($media) {
            $group = MediaGroup::where('uid', $media->media_uid)
                ->select('media_name as companyName')
                ->first();
            return array_merge($media->toArray(), [
                'companyName' => $group->companyName ?? '',
            ]);
        }

        return [];
    }

    /**
     * Format result into HTML for modal body
     */
    private function formatResult(array $data): string
    {
        $sessions = !empty($data['sessions'])
            ? '<br/><strong>Sessions</strong><br/>' . collect($data['sessions'])->map(fn ($session) => e($session))->join('<br/>')
            : '';

        return sprintf(
            '<p>%s %s<br/>%s<br/>%s<br/>%s<br/>%s%s<br/><strong>Verified</strong></p>',
            e($data['name'] ?? ''),
            e($data['another_name'] ?? ''),
            e($data['designation'] ?? ''),
            e($data['identity'] ?? ''),
            e($data['companyName'] ?? ''),
            e($data['code'] ?? ''),
            $sessions
        );
    }

    public function render()
    {
        return view('livewire.scan-user-component');
    }
}
