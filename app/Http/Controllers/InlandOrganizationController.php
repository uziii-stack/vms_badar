<?php

namespace App\Http\Controllers;

use App\Models\inlandOrganization;
use App\Models\inlandOrganizationStaff;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class InlandOrganizationController extends Controller
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
        $permission = Permission::where('name', 'read')->first();
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
        $StaffCount = inlandOrganizationStaff::count();
        $FunctionaryCount = inlandOrganizationStaff::where('staff_type', 'Functionary')->count();
        $TemporaryCount = inlandOrganizationStaff::where('staff_type', 'Temporary')->count();
        return view('pages.organizations', ['StaffCount' => $StaffCount, 'FunctionaryCount' => $FunctionaryCount, 'TemporaryCount' => $TemporaryCount]);
    }

    public function getOrganizations()
    {
        $organizations = inlandOrganization::orderBy('company_name', 'asc')->get();
        foreach ($organizations as $key => $organization) {
            $organizations[$key]->functionaryCount = inlandOrganizationStaff::where('company_uid', $organization->uid)->where('staff_type', 'Functionary')->count();
            $organizations[$key]->functionarySent = inlandOrganizationStaff::where('company_uid', $organization->uid)->where('staff_type', 'Functionary')->where('staff_security_status', 'sent')->count();
            $organizations[$key]->functionaryPending = inlandOrganizationStaff::where('company_uid', $organization->uid)->where('staff_type', 'Functionary')->where('staff_security_status', 'pending')->count();
            $organizations[$key]->functionaryApproved = inlandOrganizationStaff::where('company_uid', $organization->uid)->where('staff_type', 'Functionary')->where('staff_security_status', 'approved')->count();
            $organizations[$key]->functionaryRejection = inlandOrganizationStaff::where('company_uid', $organization->uid)->where('staff_type', 'Functionary')->where('staff_security_status', 'rejected')->count();
            $organizations[$key]->temporaryCount = inlandOrganizationStaff::where('company_uid', $organization->uid)->where('staff_type', 'Temporary')->count();
        }
        return $organizations;
    }

    public function getStats()
    {
        $organizations = inlandOrganization::select('company_name as entity_name', 'uid as uid')->get();
        foreach ($organizations as $key => $organization) {
            $organizations[$key]->total = inlandOrganizationStaff::where('company_uid', $organization->uid)->count();
            $organizations[$key]->sent = inlandOrganizationStaff::where('company_uid', $organization->uid)->where('staff_security_status', 'sent')->count();
            $organizations[$key]->pending = inlandOrganizationStaff::where('company_uid', $organization->uid)->where('staff_security_status', 'pending')->count();
            $organizations[$key]->rejected = inlandOrganizationStaff::where('company_uid', $organization->uid)->where('staff_security_status', 'rejected')->count();
            $organizations[$key]->approved = inlandOrganizationStaff::where('company_uid', $organization->uid)->where('staff_security_status', 'approved')->count();
        }
        if ($organizations->count() > 0) {
            $organizations[$organizations->count()] = [
                'entity_name' => 'Total',
                'uid' => '',
                'sent' => $organizations->sum('sent'),
                'total' => $organizations->sum('total'),
                'pending' => $organizations->sum('pending'),
                'rejected' => $organizations->sum('rejected'),
                'approved' => $organizations->sum('approved'),
            ];
        }

        return $organizations;
    }

    public function getSpecificOrganizationStats()
    {
        $organizations = inlandOrganization::where('uid', session('user')->uid)->get(['company_name', 'uid']);
        foreach ($organizations as $key => $organization) {
            $organizations[$key]->sent = inlandOrganizationStaff::where('company_uid', $organization->uid)->where('staff_security_status', 'sent')->count();
            $organizations[$key]->pending = inlandOrganizationStaff::where('company_uid', $organization->uid)->where('staff_security_status', 'pending')->count();
            $organizations[$key]->rejected = inlandOrganizationStaff::where('company_uid', $organization->uid)->where('staff_security_status', 'rejected')->count();
            $organizations[$key]->approved = inlandOrganizationStaff::where('company_uid', $organization->uid)->where('staff_security_status', 'approved')->count();
        }
        return $organizations;
    }

    // Organization and staff render page 
    public function renderOrganisation($id)
    {
        $companyName = inlandOrganization::where('uid', $id)->first('company_name');
        $functionaryStaffLimit = inlandOrganization::where('uid', $id)->first('staff_quantity');
        $functionaryStaffUpdated = inlandOrganizationStaff::where('company_uid', $id)->where('staff_type', 'Functionary')->count();
        $functionaryStaffRemaing = $functionaryStaffLimit->staff_quantity - $functionaryStaffUpdated;
        return view('pages.organization', ['id' => $id, 'functionaryStaffLimit' => $functionaryStaffLimit, 'functionaryStaffRemaing' => $functionaryStaffRemaing, 'companyName' => $companyName]);
    }

    public function getinlandOrganizationStaff($id)
    {
        $organizationStaff = inlandOrganizationStaff::where('company_uid', $id)->get();
        foreach ($organizationStaff as $key => $staff) {
            $organizationStaff[$key]->companyName = inlandOrganization::where('uid', $staff->company_uid)->first('company_name');
            $organizationStaff[$key]->pictureUrl = asset('storage/VMS/'. $staff->uid . '.png') . '?t=' . time() ;
        }
        return $organizationStaff;
    }

    public function addinlandOrganizationStaffRender($id, $staffId = null)
    {
        $staff = $staffId ? inlandOrganizationStaff::where('uid', $staffId)->first() : null;
        $functionaryStaffLimit = $id ? inlandOrganization::where('uid', $id)->first('staff_quantity') : null;
        $functionaryStaffSaturated = $id ? (inlandOrganizationStaff::where('company_uid', $id)->where('staff_type', 'Functionary')->count() < $functionaryStaffLimit->staff_quantity ? false : true) : null;
        return view('pages.addinlandOrganizationStaff', ['company_uid' => $id, 'staff' => $staff, 'functionaryStaffSaturated' => $functionaryStaffSaturated]);
    }

    public function addinlandOrganizationStaff(Request $req, $id, $staffId = null)
    {
        $organizationStaff = new inlandOrganizationStaff();
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
                return $req->submitMore ? redirect('organization/' . $id . '/' . 'addinlandOrganizationStaff/' . $organizationStaff->uid)->with('message', 'Organisation has been updated Successfully') : redirect()->route('pages.organization', $id)->with('message', 'Staff has been updated Successfully');
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            if ($exception->errorInfo[2]) {
                return  redirect()->back()->with('error', 'Error : ' . $exception->errorInfo[2]);
            } else {
                return  redirect()->back()->with('error', $exception->errorInfo[2]);
            }
        }
    }

    public function updateinlandOrganizationStaff(Request $req, $staffId)
    {
        $arrayToBeUpdate = [];
        foreach ($req->all() as $key => $value) {
            if ($key != 'submit' &&  $key != 'submitMore' && $key != '_token' && strlen($value) > 0) {
                $arrayToBeUpdate[$key] = $value;
            }
        }
        try {
            $updatedOrganisationStaff = inlandOrganizationStaff::where('uid', $staffId)->update($arrayToBeUpdate);
            $company_uid = inlandOrganizationStaff::where('uid', $staffId)->first('company_uid');
            if ($updatedOrganisationStaff) {
                return $req->submitMore ? redirect()->route('pages.addinlandOrganizationStaff', ['id' => $company_uid->company_uid, 'staffId' => $staffId])->with('message', 'Organisation has been updated Successfully') : redirect()->route('pages.organization', $company_uid->company_uid)->with('message', 'Staff has been updated Successfully');
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            if ($exception->errorInfo[2]) {
                return  redirect()->back()->with('error', 'Error : ' . $exception->errorInfo[2]);
            } else {
                return  redirect()->back()->with('error', $exception->errorInfo[2]);
            }
        }
    }

    public function updateOrganisationStaffSecurityStatus(Request $req)
    {
        try {
            $updatedOrganisationStaff = inlandOrganizationStaff::whereIn('uid', $req->uidArray)->update(['staff_security_status' => $req->status]);
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
            $organization = inlandOrganization::where('uid', $id)->firstOrFail();
            return view('pages.addOrganization', ['organization' => $organization]);
        } else {
            return view('pages.addOrganization');
        }
    }

    public function addOrganization(Request $req)
    {
        $organization = new inlandOrganization();
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
            $updatedOrganisation = inlandOrganization::where('uid', $id)->update($arrayToBeUpdate);
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
