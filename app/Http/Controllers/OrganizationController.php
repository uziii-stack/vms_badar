<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\OrganizationStaff;
// use App\Models\StaffImages;
// use App\Models\CnicBack;
// use App\Models\CnicFront;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrganizationController extends Controller
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
        $role = Role::where('name', 'orgRep')->first();
        // $permission = Permission::where('name', 'read')->first();
        $user->addRole($role);
        $user->givePermissions(['read', 'create', 'update', 'delete']);
    }

    // User Creatuib on request
    protected function newUserCreate($username, $email, $uid)
    {
        // $uid = (string) Str::uuid();
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

    // Main Organizations Request
    public function render()
    {
        $StaffCount = OrganizationStaff::count();
        $FunctionaryCount = OrganizationStaff::where('staff_type', 'Functionary')->count();
        $TemporaryCount = OrganizationStaff::where('staff_type', 'Temporary')->count();
        $stats = $this->getStats();
        return view('pages.organizations', ['StaffCount' => $StaffCount, 'FunctionaryCount' => $FunctionaryCount, 'TemporaryCount' => $TemporaryCount, 'stats' =>     $stats]);
    }

    public function getOrganizations()
    {
        // $organizations = Organization::orderBy('company_name', 'asc')->get();
        // foreach ($organizations as $key => $organization) {
        //     $organizations[$key]->functionaryCount = OrganizationStaff::where('company_uid', $organization->uid)->where('staff_type', 'Functionary')->count();
        //     $organizations[$key]->functionarySent = OrganizationStaff::where('company_uid', $organization->uid)->where('staff_type', 'Functionary')->where('staff_security_status', 'sent')->count();
        //     $organizations[$key]->functionaryPending = OrganizationStaff::where('company_uid', $organization->uid)->where('staff_type', 'Functionary')->where('staff_security_status', 'pending')->count();
        //     $organizations[$key]->functionaryApproved = OrganizationStaff::where('company_uid', $organization->uid)->where('staff_type', 'Functionary')->where('staff_security_status', 'approved')->count();
        //     $organizations[$key]->functionaryRejection = OrganizationStaff::where('company_uid', $organization->uid)->where('staff_type', 'Functionary')->where('staff_security_status', 'rejected')->count();
        //     $organizations[$key]->temporaryCount = OrganizationStaff::where('company_uid', $organization->uid)->where('staff_type', 'Temporary')->count();
        //     $organizations[$key]->staff_printed_quantity = OrganizationStaff::where('company_uid', $organization->uid)->sum('isPrinted');
        // }
        // return $organizations;

        // 1️⃣ Fetch all organizations first
        $organizations = Organization::orderBy('company_name', 'asc')->get();

        // 2️⃣ Fetch all staff counts in one grouped query
        $staffStats = OrganizationStaff::selectRaw("
        company_uid,
        staff_type,
        staff_security_status,
        COUNT(*) as total,
        SUM(isPrinted) as printed")
            ->groupBy('company_uid', 'staff_type', 'staff_security_status')
            ->get()
            ->groupBy('company_uid');

        // 3️⃣ Attach aggregated data efficiently
        foreach ($organizations as $org) {
            $stats = $staffStats->get($org->uid, collect());
            $org->functionaryCount = $stats->where('staff_type', 'Functionary')->sum('total');
            $org->functionarySent = $stats->where('staff_type', 'Functionary')->where('staff_security_status', 'sent')->sum('total');
            $org->functionaryPending = $stats->where('staff_type', 'Functionary')->where('staff_security_status', 'pending')->sum('total');
            $org->functionaryApproved = $stats->where('staff_type', 'Functionary')->where('staff_security_status', 'approved')->sum('total');
            $org->functionaryRejection = $stats->where('staff_type', 'Functionary')->where('staff_security_status', 'rejected')->sum('total');
            $org->temporaryCount = $stats->where('staff_type', 'Temporary')->sum('total');
            $org->staff_printed_quantity = $stats->sum('printed');
        }

        return $organizations;
    }

    public function getStats()
    {
        // $organizations = Organization::select('company_name as entity_name', 'uid as uid')->get();
        // foreach ($organizations as $key => $organization) {
        //     $organizations[$key]->total = OrganizationStaff::where('company_uid', $organization->uid)->count();
        //     $organizations[$key]->sent = OrganizationStaff::where('company_uid', $organization->uid)->where('staff_security_status', 'sent')->count();
        //     $organizations[$key]->pending = OrganizationStaff::where('company_uid', $organization->uid)->where('staff_security_status', 'pending')->count();
        //     $organizations[$key]->rejected = OrganizationStaff::where('company_uid', $organization->uid)->where('staff_security_status', 'rejected')->count();
        //     $organizations[$key]->approved = OrganizationStaff::where('company_uid', $organization->uid)->where('staff_security_status', 'approved')->count();
        //     $organizations[$key]->printed = OrganizationStaff::where('company_uid', $organization->uid)->sum('isPrinted');
        // }
        // if ($organizations->count() > 0) {
        //     $organizations[$organizations->count()] = [
        //         'entity_name' => 'Total',
        //         'uid' => '',
        //         'sent' => $organizations->sum('sent'),
        //         'total' => $organizations->sum('total'),
        //         'pending' => $organizations->sum('pending'),
        //         'rejected' => $organizations->sum('rejected'),
        //         'approved' => $organizations->sum('approved'),
        //         'printed' => $organizations->sum('printed'),
        //     ];
        // }

        // return $organizations;

        // 1️⃣ Get all organizations
        $organizations = Organization::select('company_name as entity_name', 'uid')->orderBy('company_name')->get();

        // 2️⃣ Get aggregated staff stats in ONE query
        $staffStats = OrganizationStaff::selectRaw("
            company_uid,
            staff_security_status,
            COUNT(*) as total,
            SUM(isPrinted) as printed
        ")
            ->groupBy('company_uid', 'staff_security_status')
            ->get()
            ->groupBy('company_uid');

        // 3️⃣ Attach all counts efficiently
        foreach ($organizations as $org) {
            $stats = $staffStats->get($org->uid, collect());

            $org->total = $stats->sum('total');
            $org->sent = $stats->where('staff_security_status', 'sent')->sum('total');
            $org->pending = $stats->where('staff_security_status', 'pending')->sum('total');
            $org->rejected = $stats->where('staff_security_status', 'rejected')->sum('total');
            $org->approved = $stats->where('staff_security_status', 'approved')->sum('total');
            $org->printed = $stats->sum('printed');
        }

        // 4️⃣ Add grand totals row at the end
        $organizations->push((object) [
                'entity_name' => 'Total',
                'uid' => '',
                'sent' => $organizations->sum('sent'),
                'total' => $organizations->sum('total'),
                'pending' => $organizations->sum('pending'),
                'rejected' => $organizations->sum('rejected'),
                'approved' => $organizations->sum('approved'),
                'printed' => $organizations->sum('printed'),
        ]);

        return $organizations;
    }

    public function getSpecificOrganizationStats()
    {
        $organizations = Organization::where('uid', session('user')->uid)->get(['company_name', 'uid']);
        foreach ($organizations as $key => $organization) {
            $organizations[$key]->sent = OrganizationStaff::where('company_uid', $organization->uid)->where('staff_security_status', 'sent')->count();
            $organizations[$key]->pending = OrganizationStaff::where('company_uid', $organization->uid)->where('staff_security_status', 'pending')->count();
            $organizations[$key]->rejected = OrganizationStaff::where('company_uid', $organization->uid)->where('staff_security_status', 'rejected')->count();
            $organizations[$key]->approved = OrganizationStaff::where('company_uid', $organization->uid)->where('staff_security_status', 'approved')->count();
        }
        return $organizations;
    }

    // Organization and staff render page 
    public function renderOrganisation($id)
    {
        $companyName = Organization::where('uid', $id)->first('company_name');
        $functionaryStaffLimit = Organization::where('uid', $id)->first('staff_quantity');
        $functionaryStaffUpdated = OrganizationStaff::where('company_uid', $id)->where('staff_type', 'Functionary')->count();
        $functionaryStaffRemaing = $functionaryStaffLimit->staff_quantity - $functionaryStaffUpdated;
        return view('pages.organization', ['id' => $id, 'functionaryStaffLimit' => $functionaryStaffLimit, 'functionaryStaffRemaing' => $functionaryStaffRemaing, 'companyName' => $companyName]);
    }

    public function getOrganizationStaff($id)
    {
        $organizationStaff = OrganizationStaff::where('company_uid', $id)->get();
        foreach ($organizationStaff as $key => $staff) {
            $organizationStaff[$key]->companyName = Organization::where('uid', $staff->company_uid)->first('company_name');
            $organizationStaff[$key]->pictureUrl = 'https://res.cloudinary.com/dbxbhlped/image/upload/v'.time().'/VMS/' . $staff->uid . '.png';
            // $organizationStaff[$key]->pictureUrl = asset('storage/images/' . $staff->uid . '.png') . '?t=' . time();
            // $organizationStaff[$key]->img_blob = base64_encode(file_get_contents('https://res.cloudinary.com/dbxbhlped/image/upload/Images/' . $staff->uid . '.png') . '?t=' . time() );
            // $organizationStaff[$key]->companyName = Organization::where('uid', $staff->company_uid)->first('company_name');
            // $organizationStaff[$key]->picture = StaffImages::where('uid', $staff->uid)->first('img_blob');
            // $organizationStaff[$key]->cnicfront = CnicFront::where('uid', $staff->uid)->first('img_blob');
            // $organizationStaff[$key]->cnicback = CnicBack::where('uid', $staff->uid)->first('img_blob');
        }
        return $organizationStaff;
    }

    public function addOrganizationStaffRender($id, $staffId = null)
    {
        $staff = $staffId ? OrganizationStaff::where('uid', $staffId)->first() : null;
        $functionaryStaffLimit = $id ? Organization::where('uid', $id)->first('staff_quantity') : null;
        $functionaryStaffSaturated = $id ? (OrganizationStaff::where('company_uid', $id)->where('staff_type', 'Functionary')->count() < $functionaryStaffLimit->staff_quantity ? false : true) : null;
        return view('pages.addOrganizationStaff', ['company_uid' => $id, 'staff' => $staff, 'functionaryStaffSaturated' => $functionaryStaffSaturated]);
    }

    public function addOrganizationStaff(Request $req, $id, $staffId = null)
    {
        // return  redirect()->back()->with('error', "You can not add more staff, Time limit has been reached.");
        $organizationStaff = new OrganizationStaff();
        $organizationStaff->uid = (string) Str::uuid();
        $organizationStaff->code =  $this->badge(8, $req->staff_type == "Functionary" ? "FN" : "TP");
        $organizationStaff->company_uid = $id;
        foreach ($req->all() as $key => $value) {
            if ($key != 'submit' && $key != 'submitMore' && $key != '_token' && strlen($value) > 0) {
                $organizationStaff[$key] = $value;
            }
        }
        try {
            $organizationStaffSaved = $organizationStaff->save();
            if ($organizationStaffSaved) {
                return $req->submitMore ? redirect('organization/' . $id . '/' . 'addOrganizationStaff/' . $organizationStaff->uid)->with('message', 'Organisation has been updated Successfully') : redirect()->route('pages.organization', $id)->with('message', 'Staff has been updated Successfully');
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            if ($exception->errorInfo[2]) {
                return  redirect()->back()->with('error', 'Error : ' . $exception->errorInfo[2]);
            } else {
                return  redirect()->back()->with('error', $exception->errorInfo[2]);
            }
        }
    }

    public function updateOrganizationStaff(Request $req, $staffId)
    {
        $arrayToBeUpdate = [];
        foreach ($req->all() as $key => $value) {
            if ($key != 'submit' &&  $key != 'submitMore' && $key != '_token' && strlen($value) > 0) {
                $arrayToBeUpdate[$key] = $value;
            }
        }
        try {
            $updatedOrganisationStaff = OrganizationStaff::where('uid', $staffId)->update($arrayToBeUpdate);
            $company_uid = OrganizationStaff::where('uid', $staffId)->first('company_uid');
            if ($updatedOrganisationStaff) {
                return $req->submitMore ? redirect()->route('pages.addOrganizationStaff', ['id' => $company_uid->company_uid, 'staffId' => $staffId])->with('message', 'Organisation has been updated Successfully') : redirect()->route('pages.organization', $company_uid->company_uid)->with('message', 'Staff has been updated Successfully');
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            if ($exception->errorInfo[2]) {
                return  redirect()->back()->with('error', 'Error : ' . $exception->errorInfo[2]);
            } else {
                return  redirect()->back()->with('error', $exception->errorInfo[2]);
            }
        }
    }

    public function deleteOrganizationStaff(Request $req, $id, $staffId)
    {
        try {
            $orgStaffToBeDelete = OrganizationStaff::where('uid', $staffId)->where('company_uid', $id)->delete();

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

    public function updateOrganisationStaffSecurityStatus(Request $req)
    {
        try {
            $updatedOrganisationStaff = OrganizationStaff::whereIn('uid', $req->uidArray)->update(['staff_security_status' => $req->status]);
            return $updatedOrganisationStaff ? 'Staff Status Updated Successfully' : 'Something Went Wrong';
        } catch (\Illuminate\Database\QueryException $exception) {
            if ($exception->errorInfo[2]) {
                return  redirect()->back()->withInput()->with('error', 'Error : ' . $exception->errorInfo[2]);
            } else {
                return  redirect()->back()->withInput()->with('error', $exception->errorInfo[2]);
            }
        }
    }

    // Organization Add/Update Form Render & Request
    public function addOrganizationRender($id = null)
    {
        if ($id) {
            $organization = Organization::where('uid', $id)->firstOrFail();
            return view('pages.addOrganization', ['organization' => $organization]);
        } else {
            return view('pages.addOrganization');
        }
    }

    public function addOrganization(Request $req)
    {
        // return  redirect()->back()->with('error', "You can not add more organization, Time limit has been reached.");
        $organization = new Organization();
        $organization->uid = (string) Str::uuid();
        $organization->company_rep_uid = (string) Str::uuid();
        foreach ($req->all() as $key => $value) {
            if ($key != 'submit' && $key != 'submitMore' && $key != '_token' && strlen($value) > 0) {
                $organization[$key] = $value;
            }
        }
        try {
            $organizationSaved = $organization->save();
            $userCreated = $this->newUserCreate($organization->company_rep_name, $organization->company_rep_email, $organization->uid);
            if ($organizationSaved && $userCreated) {
                return $req->submitMore ? redirect()->route('pages.addOrganization')->with('message', 'Organisation has been updated Successfully') : redirect()->route('pages.organizations')->with('message', 'Organization has been updated Successfully');
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            if ($exception->errorInfo[2]) {
                return  redirect()->back()->with('error', 'Error : ' . $exception->errorInfo[2]);
            } else {
                return  redirect()->back()->with('error', $exception->errorInfo[2]);
            }
        }
    }

    public function updateOrganization(Request $req, $id)
    {
        $arrayToBeUpdate = [];
        foreach ($req->all() as $key => $value) {
            if ($key != 'submit' && $key !=  'company_rep_email' &&  $key != 'submitMore' && $key != '_token' && strlen($value) > 0) {
                $arrayToBeUpdate[$key] = $value;
            }
        }
        try {
            $updatedOrganisation = Organization::where('uid', $id)->update($arrayToBeUpdate);
            if ($updatedOrganisation) {
                return $req->submitMore ? redirect()->route('pages.addOrganization', $id)->with('message', 'Organisation has been updated Successfully') : redirect()->route('pages.organizations')->with('message', 'Organization has been updated Successfully');
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            if ($exception->errorInfo[2]) {
                return  redirect()->back()->with('error', 'Error : ' . $exception->errorInfo[2]);
            } else {
                return  redirect()->back()->with('error', $exception->errorInfo[2]);
            }
        }
    }
}
