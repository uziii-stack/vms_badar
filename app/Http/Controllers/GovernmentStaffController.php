<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseApiController as BaseApiController;
use App\Models\Coupons;
use App\Models\GovernmentOrganization;
use Illuminate\Support\Facades\Validator;
use App\Models\GovernmentStaff;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GovernmentStaffController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ?string $orgId = '')
    {
        // return $request->orgId;
        $govtOrganizationStaff = GovernmentStaff::where('status', 1)->where('govt_org_uid', $request->orgId)->orWhere('govt_org_uid', $orgId)->with(['rank', 'govt_org', 'invited', 'staff_country', 'staff_categories', 'staff_city', 'programs', 'coupons'])->get();

        if ($request->expectsJson()) {
            try {
                return $this->sendResponse($govtOrganizationStaff, 'Government Organization Staff Retrieved successfully' . ($govtOrganizationStaff->count() ? '.' : ', No Data Exist'), 'table');
            } catch (\Illuminate\Database\QueryException $exception) {
                return response()->json($exception->errorInfo[2], 400);
            }
        }
        $programs = Program::where('status', 1)->get();
        $coupons = Coupons::where('status', 1)->get();
        $govtOrganization = GovernmentOrganization::where('status', 1)->where('uid', $request->orgId)->with(['govtStaff', 'govtOrgCity', 'govtOrgCountry', 'govtOrgGroup'])->first();
        // return ([$request->orgId,$govtOrganizationStaff,$programs,$coupons]);
        // return view('pages.govtStaff', ['orgId' => $request->orgId, 'govtOrganization' => $govtOrganization, 'programs' => $programs, 'coupons' => $coupons]);

        // return ['orgId' => $request->orgId, 'govtOrganizationStaff' => $govtOrganizationStaff, 'programs' => $programs, 'coupons' => $coupons];
        return view('pages.govtStaff', ['orgId' => $request->orgId, 'govtOrganizationStaff' => $govtOrganizationStaff, 'programs' => $programs, 'coupons' => $coupons]);
        // return view('pages.govtOrganization', ['govtOrganization' => $govtOrganization]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, ?string $orgId = '')
    {
        $govtOrganization = GovernmentOrganization::where('status', 1)->where('uid', $request->orgId)->first();
        return view('pages.addGovtStaff', ['orgId' => $request->orgId, 'govtOrganization' => $govtOrganization]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:30',
            'ranks_uid' => 'required|string|max:36',
            'govt_org_uid' => 'required|string|max:36',
            'designation' => 'required|string|max:255',
            'identity' => 'required|string|max:16',
            'address' => 'string|max:255',
            'contact' => 'required|string|regex:/^\d{9,20}$/',
            'invited_by' => 'required|integer',
            'staff_category' => 'required|integer',
            'country' => 'required|integer|max:20',
            'city' => 'required|integer|max:20',
            'car_sticker_color' => 'string|max:20',
            'car_sticker_no' => 'string|max:20',
            'invitaion_no' => 'string|max:200',
        ]);

        // return $validator->validated();
        try {
            $govtStaff = GovernmentStaff::create($validator->validated());
            return $this->sendResponse($govtStaff, 'Government Staff Created successfully.');
        } catch (\Illuminate\Database\QueryException $ex) {
            // Handle specific database errors
            if ($ex->getCode() == 23000) { // Duplicate entry error (unique constraint violation)
                return $this->sendError('Database Error: Something Went Wrong.', $ex->getMessage(), 422);
            }
            // General database error
            return $this->sendError('Database Error.', $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, ?string $staffId = '')
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, ?string $staffId = '')
    {
        $govtOrganizationStaff = GovernmentStaff::where('status', 1)->where('uid', $staffId)->with(['rank', 'invited', 'govt_org', 'staff_country', 'staff_categories', 'staff_city'])->first();
        // return $govtOrganizationStaff;
        if ($request->expectsJson()) {
            try {
                return $this->sendResponse($govtOrganizationStaff, 'Government Organization Staff Retrieved successfully' . ($govtOrganizationStaff->count() ? '.' : ', No Data Exist'), 'table');
            } catch (\Illuminate\Database\QueryException $exception) {
                return response()->json($exception->errorInfo[2], 400);
            }
        }
        // return $govtOrganizationStaff;
        return view('pages.addGovtStaff', ['govtOrganizationStaff' => $govtOrganizationStaff, 'staffId' => $staffId]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req, ?string $uid)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'sometimes|string|max:30',
            'ranks_uid' => 'sometimes|string|max:36',
            'designation' => 'sometimes|string|max:255',
            'identity' => 'sometimes|string|max:16',
            'address' => 'sometimes|max:255',
            'contact' => 'sometimes|string|regex:/^\d{9,20}$/',
            'invited_by' => 'sometimes|integer',
            'staff_category' => 'sometimes|integer',
            'country' => 'sometimes|integer|max:20',
            'city' => 'sometimes|integer|max:20',
            'car_sticker_color' => 'sometimes|string|max:20',
            'car_sticker_no' => 'sometimes|string|max:20',
            'invitaion_no' => 'sometimes|string|max:200',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        try {
            // return $validator->validated();
            $govtOrganization = GovernmentStaff::where('uid', $uid)->first();
            $govtOrganization->update($validator->validated());
            return $this->sendResponse($govtOrganization, 'Government Organization Staff Updated successfully.');
        } catch (\Illuminate\Database\QueryException $ex) {
            // Handle specific database errors
            if ($ex->getCode() == 23000) { // Duplicate entry error (unique constraint violation)
                return $this->sendError('Database Error: Something Went Wrong.', $ex->getMessage(), 422);
            }
            // General database error
            return $this->sendError('Database Error.', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $uuid)
    {
        try {
            $govtOrganization = GovernmentStaff::where('uid', $uuid)->where('status', 1)->update(['status' => 0]);
            return $this->sendResponse($govtOrganization, 'Government Organization Staff Deleted successfully.');
        } catch (\Illuminate\Database\QueryException $ex) {
            // Handle specific database errors
            if ($ex->getCode() == 23000) { // Duplicate entry error (unique constraint violation)
                return $this->sendError('Database Error: Something Went Wrong.', $ex->getMessage(), 422);
            }
            // General database error
            return $this->sendError('Database Error.', $ex->getMessage());
        }
    }

    public function attachProgram(Request $req)
    {
        if (isset($req['programUids[]'])) {
            $req['programUids'] = $req['programUids[]'];
        }
        $validator = Validator::make($req->all(), [
            'staffUids' => 'required|array|min:1',
            'staffUids.*' => 'required|string|exists:government_staff,uid',
            'programUids' => 'required|array|min:1',
            'programUids.*' => 'required|string|exists:programs,program_uid',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        try {
            // Get staff IDs from UIDs
            $staffIds = GovernmentStaff::whereIn('uid', $req->staffUids)->pluck('id');

            // Get program IDs from UIDs
            $programIds = Program::whereIn('program_uid', $req->programUids)->pluck('id');
            $programs = Program::whereIn('program_uid', $req->programUids)->get();
            // Attach programs to each staff
            foreach ($staffIds as $staffId) {
                $staff = GovernmentStaff::find($staffId);
                if ($staff) {
                    $staff->programs()->sync($programIds);
                }
            }
            return $this->sendResponse($programs, 'Programs attached to staff successfully.');
        } catch (\Throwable $e) {
            Log::error('Failed to sync programs', ['error' => $e->getMessage()]);
            return $this->sendError('Error syncing programs.', $e->getMessage(), 500);
        }
    }
    public function deAttachProgram(Request $req)
    {
        if (isset($req['programUids[]'])) {
            $req['programUids'] = $req['programUids[]'];
        }
        $validator = Validator::make($req->all(), [
            'staffUids' => 'required|array|min:1',
            'staffUids.*' => 'required|string|exists:government_staff,uid',
            'programUids' => 'required|array|min:1',
            'programUids.*' => 'required|string|exists:programs,program_uid',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        try {
            $staffIds = GovernmentStaff::whereIn('uid', $req->staffUids)->pluck('id');
            $programIds = Program::whereIn('program_uid', $req->programUids)->pluck('id');


            foreach ($staffIds as $staffId) {
                $staff = GovernmentStaff::find($staffId);
                if ($staff) {
                    $staff->programs()->detach($programIds);
                }
            }
            $govtStaffUpdated = GovernmentStaff::whereIn('uid', $req->staffUids)->with('programs')->first();
            // return $govtStaffUpdated->programs;
            // $programs = $govtStaffUpdated->programs;
            return $this->sendResponse([], 'Programs detached from staff successfully.');
        } catch (\Throwable $e) {
            Log::error('Failed to detach programs', ['error' => $e->getMessage()]);
            return $this->sendError('Error detaching programs.', $e->getMessage(), 500);
        }
    }

    public function attachCoupon(Request $req)
    {
        if (isset($req['couponUids[]'])) {
            $req['couponUids'] = $req['couponUids[]'];
        }
        $validator = Validator::make($req->all(), [
            'staffUids' => 'required|array|min:1',
            'staffUids.*' => 'required|string|exists:government_staff,uid',
            'couponUids' => 'required|array|min:1',
            'couponUids.*' => 'required|string|exists:coupons,coupon_uid',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        try {
            // Get staff IDs from UIDs
            $staffIds = GovernmentStaff::whereIn('uid', $req->staffUids)->pluck('id');

            // Get program IDs from UIDs
            $couponIds = Coupons::whereIn('coupon_uid', $req->couponUids)->pluck('id');
            $coupons = Coupons::whereIn('coupon_uid', $req->couponUids)->get();

            // Attach coupons to each staff
            foreach ($staffIds as $staffId) {
                $staff = GovernmentStaff::find($staffId);
                if ($staff) {
                    $staff->coupons()->sync($couponIds);
                }
            }
            return $this->sendResponse($coupons, 'Coupons attached to staff successfully.');
        } catch (\Throwable $e) {
            Log::error('Failed to sync coupons', ['error' => $e->getMessage()]);
            return $this->sendError('Error syncing coupons.', $e->getMessage(), 500);
        }
    }
    public function deAttachCoupon(Request $req)
    {
        if (isset($req['couponUids[]'])) {
            $req['couponUids'] = $req['couponUids[]'];
        }
        $validator = Validator::make($req->all(), [
            'staffUids' => 'required|array|min:1',
            'staffUids.*' => 'required|string|exists:government_staff,uid',
            'couponUids' => 'required|array|min:1',
            'couponUids.*' => 'required|string|exists:coupons,coupon_uid',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        try {
            $staffIds = GovernmentStaff::whereIn('uid', $req->staffUids)->pluck('id');
            $couponIds = Coupons::whereIn('coupon_uid', $req->couponUids)->pluck('id');
            // $coupons = Coupons::whereIn('coupon_uid', $req->couponUids)->get();

            foreach ($staffIds as $staffId) {
                $staff = GovernmentStaff::find($staffId);
                if ($staff) {
                    $staff->coupons()->detach($couponIds);
                }
            }
            return $this->sendResponse([], 'Coupons detached from staff successfully.');
        } catch (\Throwable $e) {
            Log::error('Failed to detach programs', ['error' => $e->getMessage()]);
            return $this->sendError('Error detaching programs.', $e->getMessage(), 500);
        }
    }
}
