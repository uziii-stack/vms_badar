<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Rank, Role, Permission, DepoGuest, DepoGroup, Event};
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DepoGuestImport;
use Maatwebsite\Excel\Validators\ValidationException as ExcelValidationException;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class DepoGroupController extends Controller
{
    protected function codeIdentifier($type)
    {

        $code = '';
        switch ($type) {
            case 'Trade_visitor':
                $code = 'TV';
                break;
            case 'Volunteer':
                $code = 'VL';
                break;
            case 'Local_delegate':
                $code = 'LD';
                break;
            case 'Organiser':
                $code = 'OR';
                break;
            case 'Event_manager':
                $code = 'EM';
                break;
            default:
                $code = 'HR';
                break;
        }

        return $code;
    }

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
        $role = Role::where('name', 'depoRep')->first();
        $permission = Permission::where('name', 'read')->first();
        $user->addRole($role);
        $user->givePermissions(['read', 'create', 'update', 'delete']);
    }

    // User Creat user on request
    protected function newUserCreate($username, $email, $uid)
    {
        $userAlreadyExist = User::where('email', $email)->first();
        if ($userAlreadyExist) {
            return false;
        }
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


    // Main Depo Groups Request
    public function render(Request $req)
    {
        $StaffCount = DepoGuest::count();
        return view('pages.depoGroups', ['StaffCount' => $StaffCount,'status' => $req->status]);
    }

    //  Main Depo Group Data
    public function getDepoGroups(Request $req)
    {
        // $depoGroups = DepoGroup::orderBy('depo_rep_name', 'asc')->with(['category'])->get();
        // foreach ($depoGroups as $key => $depoGroup) {
        //     $depoGroups[$key]->guestCount = DepoGuest::where('depo_uid', $depoGroup->uid)->count();
        //     $depoGroups[$key]->staff_printed_quantity = DepoGuest::where('depo_uid', $depoGroup->uid)->sum('isPrinted');
        // }
        // return $depoGroups;

        $depoGroups = DepoGroup::where('depo_status', $req->status)->with('category')->orderBy('depo_rep_name', 'asc')->get();
        $depoGuests = DepoGuest::select('depo_uid', 'isPrinted')->get()->groupBy('depo_uid');

        foreach ($depoGroups as $key => $depoGroup) {
            $guests = $depoGuests->get($depoGroup->uid, collect());
            $depoGroups[$key]->guestCount = $guests->count();
            $depoGroups[$key]->staff_printed_quantity = $guests->sum('isPrinted');
        }

        return $depoGroups;
    }

    //  Main Depo Group Stats
    public function getStats()
    {
        // $depoGroups = DepoGroup::all(['depo_name', 'uid']);
        // foreach ($depoGroups as $key => $depoGroup) {
        //     $depoGroups[$key]->sent = DepoGuest::where('depo_uid', $depoGroup->uid)->where('depoStaff_security_status', 'sent')->count();
        //     $depoGroups[$key]->pending = DepoGuest::where('depo_uid', $depoGroup->uid)->where('depoStaff_security_status', 'pending')->count();
        //     $depoGroups[$key]->rejected = DepoGuest::where('depo_uid', $depoGroup->uid)->where('depoStaff_security_status', 'rejected')->count();
        //     $depoGroups[$key]->approved = DepoGuest::where('depo_uid', $depoGroup->uid)->where('depoStaff_security_status', 'approved')->count();
        //     $depoGroups[$key]->staff_printed_quantity = DepoGuest::where('depo_uid', $depoGroup->uid)->sum('isPrinted');
        // }
        // return $depoGroups;
        $depoGroups = DepoGroup::select('depo_name as entity_name', 'uid')->get();
        $depoGuests = DepoGuest::select('depo_uid', 'depoStaff_security_status', 'isPrinted')->get()->groupBy('depo_uid');

        foreach ($depoGroups as $key => $depoGroup) {
            $guests = $depoGuests->get($depoGroup->uid, collect());

            $depoGroups[$key]->total = $guests->count();
            $depoGroups[$key]->sent = $guests->where('depoStaff_security_status', 'sent')->count();
            $depoGroups[$key]->pending = $guests->where('depoStaff_security_status', 'pending')->count();
            $depoGroups[$key]->rejected = $guests->where('depoStaff_security_status', 'rejected')->count();
            $depoGroups[$key]->approved = $guests->where('depoStaff_security_status', 'approved')->count();
            $depoGroups[$key]->staff_printed_quantity = $guests->sum('isPrinted');
        }

        if ($depoGroups->count() > 0) {
            $depoGroups[$depoGroups->count()] = [
                'entity_name' => 'Total',
                'uid' => '',
                'sent' => $depoGroups->sum('sent'),
                'total' => $depoGroups->sum('total'),
                'pending' => $depoGroups->sum('pending'),
                'rejected' => $depoGroups->sum('rejected'),
                'approved' => $depoGroups->sum('approved'),
                'staff_printed_quantity' => $depoGroups->sum('staff_printed_quantity'),
            ];
        }

        return $depoGroups;
    }

    // DepoGroup Add/Update Form Render
    public function addDepoGroupRender($id = null)
    {
        if ($id) {
            $depoGroups = DepoGroup::where('uid', $id)->with(['category'])->firstOrFail();
            return view('pages.addDepoGroup', ['depoGroups' => $depoGroups]);
        } else {
            return view('pages.addDepoGroup');
        }
    }

    // DepoGroup Add Request
    public function addDepoGroup(Request $req)
    {
        $depoGroup = new DepoGroup();
        $depoGroup->uid = (string) Str::uuid();
        foreach ($req->all() as $key => $value) {
            if ($key != 'submit' && $key != 'submitMore' && $key != '_token' && strlen($value) > 0) {
                $depoGroup[$key] = $value;
            }
        }
        try {
            $userCreated = $req->depo_rep_email !== null ? $this->newUserCreate($req->depo_rep_name, $req->depo_rep_email, $depoGroup->uid) : true;
            $depoGroup->depo_rep_uid = $req->depo_rep_email && $userCreated !== null ? $depoGroup->uid : null;
            $depoGroupsSaved = $depoGroup->save();
            if ($depoGroupsSaved) {
                return $req->submitMore ? redirect()->route('pages.addDepoGroup')->with('message', 'Depo Group has been updated Successfully') : redirect()->route('pages.depoGroups')->with('message', 'Depo Group has been updated Successfully');
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            if ($exception->errorInfo[2]) {
                return  redirect()->back()->withInput()->with('error', 'Error : ' . $exception->errorInfo[2]);
            } else {
                return  redirect()->back()->withInput()->with('error', $exception->errorInfo[2]);
            }
        }
    }

    // DepoGroup Update Request
    public function updateDepoGroup(Request $req, $id)
    {
        $arrayToBeUpdate = [];
        foreach ($req->all() as $key => $value) {
            if ($key != 'submit' &&  $key != 'depo_rep_email' && $key != 'submitMore' && $key != '_token' && strlen($value) > 0) {
                $arrayToBeUpdate[$key] = $value;
            }
        }
        try {
            $emailExist = DepoGroup::where('uid', $id)->with(['category'])->first();
            // return $req;
            if ($emailExist->depo_rep_email == null) {
                $userCreated = $this->newUserCreate($req->depo_rep_name, $req->depo_rep_email, $emailExist->uid);
                if ($userCreated) {
                    $arrayToBeUpdate['depo_rep_uid'] = $emailExist->uid;
                    $arrayToBeUpdate['depo_rep_email'] = $req->depo_rep_email;
                } else {
                    return  redirect()->back()->with('error', 'Error : User Email Already Exist Please try with another email');
                }
            }
            // return [$userCreated, $arrayToBeUpdate];
            $updatedDepo = DepoGroup::where('uid', $id)->update($arrayToBeUpdate);
            if ($updatedDepo) {
                return $req->submitMore ? redirect()->route('pages.addDepoGroup', $id)->with('message', 'Depo Group has been updated Successfully') : redirect()->route('pages.addDepoGroup')->with('message', 'Depo Group has been updated Successfully');
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            if ($exception->errorInfo[2]) {
                return  redirect()->back()->with('error', 'Error : ' . $exception->errorInfo[2]);
            } else {
                return  redirect()->back()->with('error', $exception->errorInfo[2]);
            }
        }
    }


    //  Specefic Depo Group Stats
    public function getSpecificDepoGroupStats()
    {
        $depoGroups = DepoGroup::where('uid', session('user')->uid)->get(['hr_name', 'uid']);
        foreach ($depoGroups as $key => $depoGroup) {
            $depoGroups[$key]->sent = DepoGuest::where('depo_uid', $depoGroup->uid)->where('depoStaff_security_status', 'sent')->count();
            $depoGroups[$key]->pending = DepoGuest::where('depo_uid', $depoGroup->uid)->where('depoStaff_security_status', 'pending')->count();
            $depoGroups[$key]->rejected = DepoGuest::where('depo_uid', $depoGroup->uid)->where('depoStaff_security_status', 'rejected')->count();
            $depoGroups[$key]->approved = DepoGuest::where('depo_uid', $depoGroup->uid)->where('depoStaff_security_status', 'approved')->count();
        }
        return $depoGroups;
    }

    // Depo Group Status Update
    public function depoGroupStatusUpdate(Request $req, $uid)
    {
        try {
            $updatedDepoGroup = DepoGroup::where('uid', $uid)->update(['depo_status' => $req->depo_status]);
            if ($updatedDepoGroup)
                return response()->json([
                    'success' => true,
                    'data' => $updatedDepoGroup,
                    'message' => 'Group status updated successfully.'
                ], 200);
        } catch (\Illuminate\Database\QueryException $exception) {
            if ($exception->errorInfo[2]) {
                return  redirect()->back()->with('error', 'Error : ' . $exception->errorInfo[2]);
            } else {
                return  redirect()->back()->with('error', $exception->errorInfo[2]);
            }
        }
    }


    // Depo Guest Page render
    public function depoGuestRender($id)
    {
        $depo = DepoGroup::where('uid', $id)->orWhere('depo_rep_uid', $id)->first();
        $GuestData = DepoGuest::where('depo_uid', $id)->get();
        $depoGuestLimit = DepoGroup::where('uid', $id)->first('staff_quantity');
        $depoGuestUpdated = DepoGuest::where('depo_uid', $id)->count();
        $depoGuestRemaing = $depoGuestLimit != null ? $depoGuestLimit->staff_quantity - $depoGuestUpdated : 0;
        // return [$GuestData, $depo, $depoGuestLimit, $depoGuestUpdated];
        // return [$depo];
        $events = Event::active()->orderBy('start_date', 'desc')->get();

        return view('pages.depoGroup', ['GuestCount' => $GuestData, 'id' => $id, 'depo' => $depo, 'depoGuestRemaing' => $depoGuestRemaing, 'events' => $events]);
    }

    //  Main Depo Guest Data
    public function getDepoGuest($id)
    {
        $depoGroupsGuest = DepoGuest::where('depo_uid', $id)->get();
        foreach ($depoGroupsGuest as $key => $guest) {
            $depoGroupsGuest[$key]->depoName = DepoGroup::where('uid', $guest->depo_uid)->with(['category'])->first();
            // $depoGroupsGuest[$key]->pictureUrl = asset('storage/images/' . $guest->uid . '.png') . '?t=' . time();
            $depoGroupsGuest[$key]->rank = Rank::where('id', $guest->depo_guest_rank)->first();
            $depoGroupsGuest[$key]->pictureUrl = 'https://res.cloudinary.com/dbxbhlped/image/upload/v'.time().'/VMS/' . $guest->uid . '.png';
            // $depoGroupsGuest[$key]->picture = StaffImages::where('uid', $guest->uid)->first('img_blob');
            // $depoGroupsGuest[$key]->cnicfront = CnicFront::where('uid', $guest->uid)->first('img_blob');
            // $depoGroupsGuest[$key]->cnicback = CnicBack::where('uid', $guest->uid)->first('img_blob');
        }
        return $depoGroupsGuest;
    }


    //Add DepoGroup Page render
    public function addDepoGuestRender($id, $guestId = null)
    {
        $guest = $guestId ? DepoGuest::where('uid', $guestId)->first() : null;
        $GuestLimit = $id ? DepoGroup::where('uid', $id)->first('staff_quantity') : null;
        $GuestSaturated = $id ? (DepoGuest::where('uid', $guestId)->count() < $GuestLimit?->staff_quantity ? false : true) : null;
        return view('pages.addDepoGroupGuest', ['uid' => $id, 'guest' => $guest, 'GuestSaturated' => $GuestSaturated]);
    }

    public function addDepoGroupGuestRender($id, $guestId = null)
    {
        $guest = $guestId ? DepoGuest::where('uid', $guestId)->first() : null;
        $GuestLimit = $id ? DepoGroup::where('uid', $id)->first('guest_quantity') : null;
        $GuestSaturated = $id ? (DepoGuest::where('uid', $guestId)->count() < $GuestLimit?->staff_quantity ? false : true) : null;
        return view('pages.addDepoGroupStaff', ['uid' => $id, 'guest' => $guest, 'GuestSaturated' => $GuestSaturated]);
    }

    public function addDepoGroupGuest(Request $req, $id, $guestId = null)
    {
        // return $req->hr_type;
        $depoGroupsGuest = new DepoGuest();
        $depoGroupsGuest->uid = (string) Str::uuid();
        $depoGroupsGuest->badge_type =  $this->badge(8, $this->codeIdentifier($req->badge_type));
        $depoGroupsGuest->depo_uid = $id;
        foreach ($req->all() as $key => $value) {
            if ($key != 'submit' && $key != 'submitMore' && $key != '_token' && strlen($value) > 0) {
                $depoGroupsGuest[$key] = $value;
            }
        }
        try {
            $depoGroupsGuestSaved = $depoGroupsGuest->save();
            if ($depoGroupsGuestSaved) {
                return $req->submitMore ? redirect('depoGroup/' . $id . '/' . 'addDepoGuestRender/' . $depoGroupsGuest->uid)->with('message', 'Depo Group Guest has been updated Successfully') : redirect()->route('pages.depoGroup', $id)->with('message', 'Guest has been updated Successfully');
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            if ($exception->errorInfo[2]) {
                return  redirect()->back()->with('error', 'Error : ' . $exception->errorInfo[2]);
            } else {
                return  redirect()->back()->with('error', $exception->errorInfo[2]);
            }
        }
    }

    public function updateDepoGuest(Request $req, $staffId)
    {
        $arrayToBeUpdate = [];
        $arrayToBeUpdate['badge_type'] =  $this->badge(8, $this->codeIdentifier($req->badge_type));
        foreach ($req->all() as $key => $value) {
            if ($key != 'submit' &&  $key != 'submitMore' && $key != '_token' && strlen($value) > 0) {
                $arrayToBeUpdate[$key] = $value;
            }
        }
        try {
            $updatedDepoGroup = DepoGuest::where('uid', $staffId)->update($arrayToBeUpdate);
            $uid = DepoGuest::where('uid', $staffId)->first('depo_uid');
            if ($updatedDepoGroup) {
                return redirect('depoGroup/' . $uid->depo_uid . '/' . 'addDepoGuestRender/' . $staffId)->with('message', 'Depo has been updated Successfully');
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            if ($exception->errorInfo[2]) {
                return  redirect()->back()->with('error', 'Error : ' . $exception->errorInfo[2]);
            } else {
                return  redirect()->back()->with('error', $exception->errorInfo[2]);
            }
        }
    }

    public function deleteDepoGuestRequest(Request $req, $id, $staffId)
    {
        try {
            $depoGuestToBeDelete = DepoGuest::where('uid', $staffId)->where('depo_uid', $id)->delete();

            if ($depoGuestToBeDelete) {
                Storage::disk('public')->delete('Images/' . $staffId . '.png') . '?t=' . time();
                return response()->json([
                    'success' => true,
                    'message' => 'Guest member deleted successfully.'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No matching guest member found.'
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

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.required' => 'Please select an Excel or CSV file to import.',
            'file.mimes' => 'The import file must be an xlsx, xls, or csv file.',
            'file.max' => 'The import file must not be larger than 5MB.',
        ]);

        if ($validator->fails()) {
            return $this->importErrorResponse($request, $validator->errors()->all(), 422);
        }

        try {
            $import = new DepoGuestImport;
            Excel::import($import, $request->file('file'));
        } catch (ExcelValidationException $e) {
            $messages = [];

            foreach ($e->failures() as $failure) {
                foreach ($failure->errors() as $error) {
                    $messages[] = 'Row ' . $failure->row() . ' (' . $failure->attribute() . '): ' . $error;
                }
            }

            return $this->importErrorResponse($request, $messages ?: ['Import validation failed. Please check your file data.'], 422);
        } catch (\Throwable $e) {
            return $this->importErrorResponse($request, ['Import failed: ' . $e->getMessage()], 500);
        }

        if ($request->expectsJson() || !$request->headers->has('referer')) {
            return response()->json([
                'success' => true,
                'message' => $this->importSuccessMessage($import),
            ]);
        }

        return back()->with('success', $this->importSuccessMessage($import));
    }

    protected function importSuccessMessage(DepoGuestImport $import): string
    {
        return 'Import completed. Created: ' . $import->createdCount . '. Skipped duplicates: ' . $import->skippedCount . '.';
    }

    protected function importErrorResponse(Request $request, array $messages, int $status)
    {
        if ($request->expectsJson() || !$request->headers->has('referer')) {
            return response()->json([
                'success' => false,
                'message' => 'Import failed.',
                'errors' => $messages,
            ], $status);
        }

        return back()
            ->withErrors(['file' => $messages])
            ->with('error', implode("\n", $messages))
            ->withInput();
    }

    // public function updateHrGroupStaffSecurityStatus(Request $req)
    // {
    //     try {
    //         $updatedHRStaff = HrStaff::whereIn('uid', $req->uidArray)->update(['hr_security_status' => $req->status]);
    //         return $updatedHRStaff ? 'Staff Status Updated Successfully' : 'Something Went Wrong';
    //     } catch (\Illuminate\Database\QueryException $exception) {
    //         if ($exception->errorInfo[2]) {
    //             return  redirect()->back()->with('error', 'Error : ' . $exception->errorInfo[2]);
    //         } else {
    //             return  redirect()->back()->with('error', $exception->errorInfo[2]);
    //         }
    //     }
    // }
}
