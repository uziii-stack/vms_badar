<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MediaGroup;
use App\Models\MediaStaff;
// use App\Models\StaffImages;
// use App\Models\CnicBack;
// use App\Models\CnicFront;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{

    protected function badge($characters, $prefix)
    {
        $possible = '0123456789';
        $code = $prefix;
        $i = 0;
        while ($i < $characters) {
            $code .= substr($possible, mt_rand(0, strlen($possible) - 1), 1);
            if ($i < $characters - 1) {
                $code .= "";
            }
            $i++;
        }
        return $code;
    }

    protected function basicRolesAndTeams($user)
    {
        $role = Role::where('name', 'mediaRep')->first();
        $permission = Permission::where('name', 'read')->first();
        $user->addRole($role);
        $user->givePermissions(['read', 'create', 'update', 'delete']);
    }

    // User Create on request

    protected function newUserCreate($username, $email, $uid)
    {
        $pass = Str::password(12, true, true, true, false);
        $user = new User();
        $user->uid = $uid;
        $user->name = $username;
        $user->email = $email;
        $user->password = Hash::make($pass);
        $user->activation_code = $this->badge(8, "");
        $user->activated = 1;
        $savedUser = 0;
        try {
            $savedUser = $user->save();
            $this->basicRolesAndTeams($user);
            if ($savedUser) {
                $emailSent = (new AccountCreationController)->html_email($uid, $pass);
                return $emailSent ? true : false;
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            if ($exception->errorInfo[2]) {
                return  redirect()->back()->with('error', 'Email Address already Exist error : ' . $exception->errorInfo[2]);
            } else {
                return  redirect()->back()->with('error', $exception->errorInfo[2]);
            }
        }
    }

    // Media Groups
    public function render()
    {
        $StaffCount = MediaStaff::count();
        return view('pages.mediaGroups', ['StaffCount' => $StaffCount]);
    }

    public function getMedia()
    {
        // $mediaGroups = MediaGroup::all();
        // foreach ($mediaGroups as $key => $mediaGroup) {
        //     $mediaGroups[$key]->functionaryCount = MediaStaff::where('media_uid', $mediaGroup->uid)->where('employee_type', 'Functionary')->count();
        //     $mediaGroups[$key]->temporaryCount = MediaStaff::where('media_uid', $mediaGroup->uid)->where('employee_type', 'Temporary')->count();
        //     $mediaGroups[$key]->functionarySent = MediaStaff::where('media_uid', $mediaGroup->uid)->where('employee_type', 'Functionary')->where('media_staff_security_status', 'sent')->count();
        //     $mediaGroups[$key]->functionaryPending = MediaStaff::where('media_uid', $mediaGroup->uid)->where('employee_type', 'Functionary')->where('media_staff_security_status', 'pending')->count();
        //     $mediaGroups[$key]->functionaryApproved = MediaStaff::where('media_uid', $mediaGroup->uid)->where('employee_type', 'Functionary')->where('media_staff_security_status', 'approved')->count();
        //     $mediaGroups[$key]->functionaryRejection = MediaStaff::where('media_uid', $mediaGroup->uid)->where('employee_type', 'Functionary')->where('media_staff_security_status', 'rejected')->count();
        //     $mediaGroups[$key]->staff_printed_quantity = MediaStaff::where('media_uid', $mediaGroup->uid)->sum('isPrinted');
        // }
        // return $mediaGroups;
        // 1️⃣ Fetch all media groups
        $mediaGroups = MediaGroup::orderBy('media_name', 'asc')->get();

        // 2️⃣ Aggregate MediaStaff data in one query
        $staffStats = MediaStaff::selectRaw("
            media_uid,
            employee_type,
            media_staff_security_status,
            COUNT(*) as total,
            SUM(isPrinted) as printed
        ")
            ->groupBy('media_uid', 'employee_type', 'media_staff_security_status')
            ->get()
            ->groupBy('media_uid');

        // 3️⃣ Merge stats into mediaGroups
        foreach ($mediaGroups as $group) {
            $stats = $staffStats->get($group->uid, collect());

            $group->functionaryCount = $stats->where('employee_type', 'Functionary')->sum('total');
            $group->temporaryCount = $stats->where('employee_type', 'Temporary')->sum('total');
            $group->functionarySent = $stats->where('employee_type', 'Functionary')->where('media_staff_security_status', 'sent')->sum('total');
            $group->functionaryPending = $stats->where('employee_type', 'Functionary')->where('media_staff_security_status', 'pending')->sum('total');
            $group->functionaryApproved = $stats->where('employee_type', 'Functionary')->where('media_staff_security_status', 'approved')->sum('total');
            $group->functionaryRejection = $stats->where('employee_type', 'Functionary')->where('media_staff_security_status', 'rejected')->sum('total');
            $group->staff_printed_quantity = $stats->sum('printed');
        }

        return $mediaGroups;
    }

    public function addMedia($id = null)
    {
        if ($id) {
            $mediaGroup = MediaGroup::where('uid', $id)->firstOrFail();
            return view('pages.addMediaGroup', ['mediagroup' => $mediaGroup]);
        } else {
            return view('pages.addMediaGroup');
        }
    }

    public function getStats()
    {
        // $mediaGroups = MediaGroup::select('media_name as entity_name', 'uid as uid')->get();
        // foreach ($mediaGroups as $key => $mediaGroup) {
        //     $mediaGroups[$key]->total = MediaStaff::where('media_uid', $mediaGroup->uid)->count();
        //     $mediaGroups[$key]->sent = MediaStaff::where('media_uid', $mediaGroup->uid)->where('media_staff_security_status', 'sent')->count();
        //     $mediaGroups[$key]->pending = MediaStaff::where('media_uid', $mediaGroup->uid)->where('media_staff_security_status', 'pending')->count();
        //     $mediaGroups[$key]->rejected = MediaStaff::where('media_uid', $mediaGroup->media_uid)->where('media_staff_security_status', 'rejected')->count();
        //     $mediaGroups[$key]->approved = MediaStaff::where('media_uid', $mediaGroup->uid)->where('media_staff_security_status', 'approved')->count();
        //     $mediaGroups[$key]->staff_printed_quantity = MediaStaff::where('media_uid', $mediaGroup->uid)->sum('isPrinted');
        // }

        // if ($mediaGroups->count() > 0) {
        //     $mediaGroups[$mediaGroups->count()] = [
        //         'entity_name' => 'Total',
        //         'uid' => '',
        //         'sent' => $mediaGroups->sum('sent'),
        //         'total' => $mediaGroups->sum('total'),
        //         'pending' => $mediaGroups->sum('pending'),
        //         'rejected' => $mediaGroups->sum('rejected'),
        //         'approved' => $mediaGroups->sum('approved'),
        //     ];
        // }
        // return $mediaGroups;
        // 1️⃣ Get all media groups
        $mediaGroups = MediaGroup::select('media_name as entity_name', 'uid')->orderBy('media_name', 'asc')->get();

        // 2️⃣ One grouped query for staff stats
        $staffStats = MediaStaff::selectRaw("
            media_uid,
            media_staff_security_status,
            COUNT(*) as total,
            SUM(isPrinted) as printed
        ")
            ->groupBy('media_uid', 'media_staff_security_status')
            ->get()
            ->groupBy('media_uid');

        // 3️⃣ Merge results
        foreach ($mediaGroups as $group) {
            $stats = $staffStats->get($group->uid, collect());

            $group->total = $stats->sum('total');
            $group->sent = $stats->where('media_staff_security_status', 'sent')->sum('total');
            $group->pending = $stats->where('media_staff_security_status', 'pending')->sum('total');
            $group->rejected = $stats->where('media_staff_security_status', 'rejected')->sum('total');
            $group->approved = $stats->where('media_staff_security_status', 'approved')->sum('total');
            $group->staff_printed_quantity = $stats->sum('printed');
        }

        // 4️⃣ Add total summary row
        if ($mediaGroups->isNotEmpty()) {
            $mediaGroups->push((object)[
                'entity_name' => 'Total',
                'uid' => '',
                'sent' => $mediaGroups->sum('sent'),
                'total' => $mediaGroups->sum('total'),
                'pending' => $mediaGroups->sum('pending'),
                'rejected' => $mediaGroups->sum('rejected'),
                'approved' => $mediaGroups->sum('approved'),
                'staff_printed_quantity' => $mediaGroups->sum('staff_printed_quantity'),
            ]);
        }

        return $mediaGroups;
    }

    public function addMediaRequest(Request $req)
    {
        $mediaGroup = new MediaGroup();
        $mediaGroup->uid = (string) Str::uuid();
        $mediaGroup->media_rep_uid = (string) Str::uuid();
        foreach ($req->all() as $key => $value) {
            if ($key != 'submit' && $key != 'submitMore' && $key != '_token' && strlen($value) > 0) {
                $mediaGroup[$key] = $value;
            }
        }
        try {
            $mediaGroupSaved = $mediaGroup->save();
            $userCreated = $this->newUserCreate($mediaGroup->media_rep_name, $mediaGroup->media_rep_email, $mediaGroup->uid);
            if ($mediaGroupSaved && $userCreated) {
                return $req->submitMore ? redirect()->route('pages.addMedia')->with('message', 'Media Group has been updated Successfully') : redirect()->route('pages.media')->with('message', 'Media Group has been updated Successfully');
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            if ($exception->errorInfo[2]) {
                return  redirect()->back()->with('error', 'Error : ' . $exception->errorInfo[2]);
            } else {
                return  redirect()->back()->with('error', $exception->errorInfo[2]);
            }
        }
    }

    public function updateMedia(Request $req, $id)
    {
        $arrayToBeUpdate = [];
        foreach ($req->all() as $key => $value) {
            if ($key != 'submit' && $key !=  'media_rep_email' &&  $key != 'submitMore' && $key != '_token' && strlen($value) > 0) {
                $arrayToBeUpdate[$key] = $value;
            }
        }
        try {
            $updatedMedia = MediaGroup::where('uid', $id)->update($arrayToBeUpdate);
            if ($updatedMedia) {
                return $req->submitMore ? redirect()->route('pages.addMedia', $id)->with('message', 'Media has been updated Successfully') : redirect()->route('pages.mediaGroups')->with('message', 'Media has been updated Successfully');
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            if ($exception->errorInfo[2]) {
                return  redirect()->back()->with('error', 'Error : ' . $exception->errorInfo[2]);
            } else {
                return  redirect()->back()->with('error', $exception->errorInfo[2]);
            }
        }
    }


    public function deleteMediaStaffRequest(Request $req, $id, $staffId)
    {
        try {
            $orgStaffToBeDelete = MediaStaff::where('uid', $staffId)->where('media_uid', $id)->delete();

            if ($orgStaffToBeDelete) {
                Storage::disk('public')->delete('Images/' . $staffId . '.png') . '?t=' . time();
                return response()->json([
                    'success' => true,
                    'message' => 'Staff member deleted successfully.'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No matching staff member found.'
                ], 404);
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . ($exception->errorInfo[2] ?? 'Unknown error')
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    // Media staff render page 
    public function rendermediaGroup($id)
    {
        $mediaName = MediaGroup::where('uid', $id)->first('media_name');
        $functionaryStaffLimit = MediaGroup::where('uid', $id)->first('staff_quantity');
        $functionaryStaffUpdated = MediaStaff::where('media_uid', $id)->count();
        $functionaryStaffRemaing = $functionaryStaffLimit ? $functionaryStaffLimit->staff_quantity - $functionaryStaffUpdated : 0;
        return view('pages.mediaGroup', ['id' => $id, 'functionaryStaffLimit' => $functionaryStaffLimit, 'functionaryStaffRemaing' => $functionaryStaffRemaing, 'mediaName' => $mediaName]);
        // return $functionaryStaffLimit;
    }

    public function getMediaStaff($id)
    {
        $mediaStaff = MediaStaff::where('media_uid', $id)->get();
        foreach ($mediaStaff as $key => $staff) {
            $mediaStaff[$key]->mediaName = MediaGroup::where('uid', $staff->media_uid)->first('media_name');
            $mediaStaff[$key]->pictureUrl = 'https://res.cloudinary.com/dbxbhlped/image/upload/v'.time().'/VMS/' . $staff->uid . '.png';
            // $mediaStaff[$key]->pictureUrl = asset('storage/images/' . $staff->uid . '.png') . '?t=' . time();
            // $mediaStaff[$key]->picture = StaffImages::where('uid', $staff->uid)->first('img_blob');
            // $mediaStaff[$key]->cnicfront = CnicFront::where('uid', $staff->uid)->first('img_blob');
            // $mediaStaff[$key]->cnicback = CnicBack::where('uid', $staff->uid)->first('img_blob');
        }
        return $mediaStaff;
    }

    public function getSpecificMediaStats()
    {
        $mediagroups = MediaGroup::where('uid', session('user')->uid)->get(['media_name', 'uid']);
        foreach ($mediagroups as $key => $media) {
            $mediagroups[$key]->sent = MediaStaff::where('media_uid', $media->uid)->where('media_staff_security_status', 'sent')->count();
            $mediagroups[$key]->pending = MediaStaff::where('media_uid', $media->uid)->where('media_staff_security_status', 'pending')->count();
            $mediagroups[$key]->rejected = MediaStaff::where('media_uid', $media->uid)->where('media_staff_security_status', 'rejected')->count();
            $mediagroups[$key]->approved = MediaStaff::where('media_uid', $media->uid)->where('media_staff_security_status', 'approved')->count();
            $mediagroups[$key]->staff_printed_quantity = MediaStaff::where('media_uid', $media->uid)->sum('isPrinted');
        }
        return $mediagroups;
    }

    public function addMediaStaffRender($id, $staffId = null)
    {
        $staff = $staffId ? MediaStaff::where('uid', $staffId)->first() : null;
        $functionaryStaffLimit = $id ? MediaGroup::where('uid', $id)->first('staff_quantity') : null;
        $functionaryStaffSaturated = $id ? (MediaStaff::whereNotNull('media_uid')->where('media_uid', $id)->count() < $functionaryStaffLimit->staff_quantity ? false : true) : null;
        return view('pages.addMediaStaff', ['media_uid' => $id, 'staff' => $staff, 'functionaryStaffSaturated' => $functionaryStaffSaturated]);
        // return $staff;
    }

    public function addMediaStaff(Request $req, $id, $staffId = null)
    {
        $mediaStaff = new MediaStaff();
        $mediaStaff->uid = (string) Str::uuid();
        $mediaStaff->code = $this->badge(8, "ME");
        $mediaStaff->media_uid = $id;
        foreach ($req->all() as $key => $value) {
            if ($key != 'submit' && $key != 'submitMore' && $key != '_token' && strlen($value) > 0) {
                $mediaStaff[$key] = $value;
            }
        }
        try {
            $mediaStaffSaved = $mediaStaff->save();
            if ($mediaStaffSaved) {
                return $req->submitMore ? redirect('mediaGroup/' . $id . '/' . 'addMediaStaff/' . $mediaStaff->uid)->with('message', 'Media Group has been updated Successfully') : redirect()->route('pages.mediaGroup', $id)->with('message', 'Staff has been updated Successfully');
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            if ($exception->errorInfo[2]) {
                return  redirect()->back()->withInput()->with('error', 'Error : ' . $exception->errorInfo[2]);
            } else {
                return  redirect()->back()->withInput()->with('error', $exception->errorInfo[2]);
            }
        }
    }

    public function updateMediaStaff(Request $req, $staffId)
    {
        $arrayToBeUpdate = [];
        foreach ($req->all() as $key => $value) {
            if ($key != 'submit' &&  $key != 'submitMore' && $key != '_token' && strlen($value) > 0) {
                $arrayToBeUpdate[$key] = $value;
            }
        }
        try {
            $updatedOrganisationStaff = MediaStaff::where('uid', $staffId)->update($arrayToBeUpdate);
            $media_uid = MediaStaff::where('uid', $staffId)->first('media_uid');
            if ($updatedOrganisationStaff) {
                return $req->submitMore ? redirect()->route('pages.addMediaStaffRender', ['id' => $media_uid->media_uid, 'staffId' => $staffId])->with('message', 'Organisation has been updated Successfully') : redirect()->route('pages.organization', $media_uid->media_uid)->with('message', 'Staff has been updated Successfully');
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            if ($exception->errorInfo[2]) {
                return  redirect()->back()->with('error', 'Error : ' . $exception->errorInfo[2]);
            } else {
                return  redirect()->back()->with('error', $exception->errorInfo[2]);
            }
        }
    }

    public function updateMediaStaffSecurityStatus(Request $req)
    {
        try {
            $updatedMediaStaff = MediaStaff::whereIn('uid', $req->uidArray)->update(['media_staff_security_status' => $req->status]);
            return $updatedMediaStaff ? 'Staff Status Updated Successfully' : 'Something Went Wrong';
        } catch (\Illuminate\Database\QueryException $exception) {
            if ($exception->errorInfo[2]) {
                return  redirect()->back()->with('error', 'Error : ' . $exception->errorInfo[2]);
            } else {
                return  redirect()->back()->with('error', $exception->errorInfo[2]);
            }
        }
    }

    public function mediaAllStaff()
    {
        return view('pages.mediaAllStaff');
    }

    public function requestMediaAllStaff()
    {
        $mediaStaff = MediaStaff::all();
        foreach ($mediaStaff as $key => $staff) {
            $mediaStaff[$key]->mediaName = MediaGroup::where('uid', $staff->media_uid)->first('media_name');
            $mediaStaff[$key]->pictureUrl = 'https://res.cloudinary.com/dbxbhlped/image/upload/v'.time().'/VMS/' . $staff->uid . '.png';
            // $mediaStaff[$key]->pictureUrl = asset('storage/images/' . $staff->uid . '.png') . '?t=' . time();
        }
        return $mediaStaff;
    }
}
