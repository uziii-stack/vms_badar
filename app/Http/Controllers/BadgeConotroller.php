<?php

namespace App\Http\Controllers;

use App\Models\DepoGroup;
use App\Models\Event;
use App\Models\Organization;
use App\Models\DepoGuest;
use App\Models\HrGroup;
use App\Models\HrStaff;
use App\Models\MediaGroup;
use App\Models\MediaStaff;
use App\Models\OrganizationStaff;
use App\Models\Template;
use App\Models\TemporaryPass;
use App\Models\Visitors;
use Illuminate\Http\Request;

class BadgeConotroller extends Controller
{
    public function render(Request $req, $type, $ids, $flag = false)
    {
        $arr = explode(",", $ids);
        $data = [];
        switch ($type) {
            case 'org':
                $data = OrganizationStaff::whereIn('id', $arr)->where('staff_security_status', 'approved')->get();
                foreach ($data as $key => $dataList) {
                    $printQuantity = $dataList->isPrinted + 1;
                    OrganizationStaff::where('id', $dataList->id)->update(['isPrinted' => $printQuantity]);
                    $data[$key]->companyName = Organization::where('uid', $dataList->company_uid)->first('company_name');
                    $data[$key]->image = $flag;
                    $data[$key]->address = $data[$key]->staff_address;
                }
                break;
            case 'hr':
                $data = HrStaff::whereIn('id', $arr)->where('hr_security_status', 'approved')->get(['hr_first_name', 'hr_last_name', 'hr_designation', 'code', 'hr_identity', 'uid', 'hr_uid', 'hr_country', 'isPrinted', 'hr_address']);
                foreach ($data as $key => $dataList) {
                    $printQuantity = $dataList->isPrinted + 1;
                    HrStaff::where('uid', $dataList->uid)->update(['isPrinted' => $printQuantity]);
                    $data[$key]->companyName = HrGroup::where('uid', $dataList->hr_uid)->first('hr_name');
                    $data[$key]->companyName->company_name = $data[$key]->companyName->hr_name;
                    $data[$key]->staff_first_name = $data[$key]->hr_first_name;
                    $data[$key]->staff_last_name = $data[$key]->hr_last_name;
                    $data[$key]->staff_designation = $data[$key]->hr_designation;
                    $data[$key]->staff_identity = $data[$key]->hr_identity;
                    $data[$key]->staff_country = $data[$key]->hr_country;
                    $data[$key]->image = $flag;
                    $data[$key]->address = $data[$key]->hr_address;
                }
                break;
            case 'media':
                $data = MediaStaff::whereIn('id', $arr)->where('media_staff_security_status', 'approved')->get(['media_staff_first_name', 'media_staff_last_name', 'media_staff_designation', 'code', 'media_staff_identity', 'uid', 'media_uid', 'media_staff_country', 'isPrinted', 'media_staff_address']);
                foreach ($data as $key => $dataList) {
                    $printQuantity = $dataList->isPrinted + 1;
                    MediaStaff::where('uid', $dataList->uid)->update(['isPrinted' => $printQuantity]);
                    $data[$key]->companyName = MediaGroup::where('uid', $dataList->media_uid)->first('media_name');
                    $data[$key]->companyName->company_name = $data[$key]->companyName->media_name;
                    $data[$key]->staff_first_name = $data[$key]->media_staff_first_name;
                    $data[$key]->staff_last_name = $data[$key]->media_staff_last_name;
                    $data[$key]->staff_designation = $data[$key]->media_staff_designation;
                    $data[$key]->staff_identity = $data[$key]->media_staff_identity;
                    $data[$key]->staff_country = $data[$key]->media_staff_country;
                    $data[$key]->image = $flag;
                    $data[$key]->address = $data[$key]->media_staff_address;
                }
                break;
            case 'depo':
                $data = DepoGuest::whereIn('id', $arr)->get();
                foreach ($data as $key => $dataList) {
                    $printQuantity = $dataList->isPrinted + 1;
                    DepoGuest::where('id', $dataList->id)->update(['isPrinted' => $printQuantity]);
                    $data[$key]->companyName = DepoGroup::where('uid', $dataList->depo_uid)->first(['depo_rep_name', 'depo_category']);
                    $data[$key]->companyName->company_name = $data[$key]->companyName->depo_rep_name;
                    $data[$key]->companyName->badge_category = $data[$key]->companyName->depo_category;
                    $data[$key]->staff_first_name = $data[$key]->depo_guest_name;
                    $data[$key]->staff_last_name = '';
                    $data[$key]->code =  $data[$key]->badge_type ? $data[$key]->badge_type : 'DP' . str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
                    $data[$key]->staff_designation = $data[$key]->depo_guest_designation;
                    $data[$key]->staff_identity = $data[$key]->depo_identity;
                    $data[$key]->staff_country = 'Pakistan';
                    $data[$key]->image = $flag;
                    $data[$key]->address = $data[$key]->depo_address;
                }
                break;
            case 'badge':
                $data = Visitors::where('identity', $arr)->get(['name', 'designation', 'identity', 'code',]);
                foreach ($data as $key => $dataList) {
                    $data[$key]->staff_first_name = $data[$key]->name;
                    $data[$key]->staff_last_name = '';
                    $data[$key]->code = $data[$key]->code;
                    $data[$key]->staff_designation = $data[$key]->designation;
                    $data[$key]->staff_identity = $data[$key]->identity;
                    $data[$key]->staff_country = 'Pakistan';
                    $data[$key]->image = $flag;
                }
                break;
            default:
                $code = [];
                break;
        }
        // $data->image = StaffImages::whereIn('uid', $arr)->get('img_blob');
        // return $data;
        return view('pages.badges', ['data' => $data]);
    }
    public function printEBadge(Request $req, $ids)
    {
        $arr = explode(",", $ids);
        $data = [];
        $data = Visitors::where('identity', $arr)->latest('updated_at')->first();
        if (empty($data)) {
            $data = TemporaryPass::where('identity', $arr)->first();
        }
        $data = json_decode($data);
        $host = request()->getHttpHost();
        if ($data) {
            return view("pages.printPages.printEBadgeIndus", ["data" => $data, 'host' => $host]);
            // return view("pages.printPages.printEBadge", ["data" => $data, 'host' => $host]);
        }
    }
    public function printA4EBadge(Request $req, $event, $type, $ids)
    {
        $arr = array_values(array_unique(array_filter(explode(",", $ids))));
        $data = collect();
        $eventData = Event::where('id', $event)->where('deleted', 0)->first();
        // return [$eventData,$data];
        $typeData = [
            ['yellow', 'Trade Visitor'],
            ['blue', 'Exhibitor'],
            ['red', 'Event Manager'],
            ['green', 'Organizer'],
            ['orange', 'Guest Of Honour'],
            ['purple', 'Panelist'],
            ['teal', 'Volunteer'],
        ];
        foreach ($arr as $identity) {
            $attendee = Visitors::where('identity', $identity)->latest('updated_at')->first();

            if (!$attendee) {
                $attendee = TemporaryPass::where('identity', $identity)->latest('updated_at')->first();
            }

            if (!$attendee) {
                $depoGuest = DepoGuest::where('depo_identity', $identity)
                    ->orWhere('id', $identity)
                    ->latest('updated_at')
                    ->first();

                if ($depoGuest) {
                    $attendee = (object) [
                        'name' => $depoGuest->depo_guest_name,
                        'designation' => $depoGuest->depo_guest_designation,
                        'company' => $depoGuest->depo_guest_service,
                        'nationality' => 'Pakistan',
                        'identity' => $depoGuest->depo_identity,
                        'contact' => $depoGuest->depo_guest_contact,
                        'code' => $depoGuest->badge_type,
                        'created_at' => $depoGuest->created_at,
                    ];
                }
            }

            if (!$attendee) {
                $hrStaff = HrStaff::where('hr_identity', $identity)
                    ->orWhere('id', $identity)
                    ->latest('updated_at')
                    ->first();

                if ($hrStaff) {
                    $attendee = (object) [
                        'name' => trim($hrStaff->hr_first_name . ' ' . $hrStaff->hr_last_name),
                        'designation' => $hrStaff->hr_designation,
                        'company' => HrGroup::where('uid', $hrStaff->hr_uid)->value('hr_name') ?? '',
                        'nationality' => $hrStaff->hr_nationality ?: $hrStaff->hr_country,
                        'identity' => $hrStaff->hr_identity,
                        'contact' => $hrStaff->hr_contact,
                        'code' => $hrStaff->code,
                        'created_at' => $hrStaff->created_at,
                    ];
                }
            }

            if ($attendee) {
                $data->push($attendee);
            }
        }

        $host = request()->getHttpHost();
        if ($data->isNotEmpty()) {
            return view("pages.printPages.printA4EBadge", ["data" => $data, 'event' => $eventData, 'type' => $typeData[$type] ?? $typeData[0], 'host' => $host]);
        }
    }

    public function printIndusBadge(Request $req, $ids)
    {
        $arr = explode(",", $ids);
        $data = [];
        $data = Visitors::where('identity', $arr)->latest('updated_at')->first();
        if (empty($data)) {
            $data = TemporaryPass::where('identity', $arr)->first();
        }
        $data = json_decode($data);
        $host = request()->getHttpHost();
        // return $data;
        if ($data) {
            return view("pages.printPages.printEBadge", ["data" => $data, 'host' => $host]);
        }
    }

    public function printEventBadge(Request $req, string $event, string $ids)
    {
        $arr = array_values(array_unique(array_filter(explode(",", $ids))));
        $data = [];
        $host = request()->getHttpHost();
        $eventModel = Event::where('name', $event)->where('deleted', 0)->first();
        $template = $eventModel
            ? Template::where('type', 'A4 E-Badge')->where('event_id', $eventModel->id)->latest()->first()
            : null;

        if ($template) {
            $data = collect();

            foreach ($arr as $identity) {
                $attendee = Visitors::where('identity', $identity)->latest('updated_at')->first();

                if (!$attendee) {
                    $attendee = TemporaryPass::where('identity', $identity)->latest('updated_at')->first();
                }

                if ($attendee) {
                    $data->push($attendee);
                }
            }

            if ($data->isNotEmpty()) {
                return view("pages.printPages.printA4TemplateEBadge", [
                    "data" => $data,
                    'host' => $host,
                    'event' => $event,
                    'eventModel' => $eventModel,
                    'template' => $template,
                ]);
            }
        }

        $data = Visitors::where('identity', $arr)->latest('updated_at')->first();
        if (empty($data)) {
            $data = TemporaryPass::where('identity', $arr)->first();
        }
        $data = json_decode($data);
        // return $data;
        if ($data) {
            return view("pages.printPages.printEBadge", ["data" => $data, 'host' => $host, 'event' => $event]);
        }
    }

    public function printDelegationEnvelope(Request $req, $type, $ids, $flag = false)
    {
        $arr = explode(",", $ids);
        $data = [];
        switch ($type) {
            case 'depo':
                $data = DepoGuest::whereIn('id', $arr)->get();
                // foreach ($data as $key => $dataList) {
                //     $data[$key]->companyName = DepoGroup::where('uid', $dataList->depo_uid)->first(['depo_rep_name', 'depo_category']);
                //     $data[$key]->companyName->company_name = $data[$key]->companyName->depo_rep_name;
                //     $data[$key]->companyName->badge_category = $data[$key]->companyName->depo_category;
                //     $data[$key]->staff_first_name = $data[$key]->depo_guest_name;
                //     $data[$key]->staff_last_name = '';
                //     $data[$key]->code = 'DP' . substr($data[$key]->depo_identity, -8);
                //     $data[$key]->staff_designation = $data[$key]->depo_guest_designation;
                //     $data[$key]->staff_identity = $data[$key]->depo_identity;
                //     $data[$key]->staff_country = 'Pakistan';
                //     $data[$key]->image = $flag;
                // }
                break;
            default:
                $code = [];
                break;
        }
        // return $data;
        return view('pages.envelope', ['dataNodes' => $data]);
    }
    public function printDelegationTag(Request $req, $type, $ids, $flag = false)
    {
        $arr = explode(",", $ids);
        $data = [];
        switch ($type) {
            case 'depo':
                $data = DepoGuest::whereIn('id', $arr)->get();
                break;
            default:
                $code = [];
                break;
        }
        // return $data;
        return view('pages.printPages.printTag', ['dataNodes' => $data]);
    }

    public function printWithTemplate(Request $req, $templateId, $ids)
    {
        $arr = explode(",", $ids);
        $data = DepoGuest::whereIn('id', $arr)->get();
        $template = Template::where('id', $templateId)->first();

        if ($template && str_contains(strtolower($template->type), 'a4')) {
            $badgeData = $data->map(function ($dataNode) {
                return (object) [
                    'name' => $dataNode->depo_guest_name,
                    'designation' => $dataNode->depo_guest_designation,
                    'identity' => $dataNode->depo_identity,
                    'code' => $dataNode->badge_type,
                    'contact' => $dataNode->depo_guest_contact,
                    'company' => $dataNode->depo_guest_service,
                    'nationality' => '',
                    'sector' => '',
                    'created_at' => $dataNode->created_at,
                ];
            });

            return view("pages.printPages.printA4TemplateEBadge", [
                "data" => $badgeData,
                'host' => request()->getHttpHost(),
                'event' => 'INDUS-AI',
                'eventModel' => $template->event,
                'template' => $template,
            ]);
        }

        return view('pages.printPages.printWithTemplate', ['data' => $data, 'template' => collect([$template])]);
    }
}
