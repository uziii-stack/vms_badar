<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseApiController as BaseApiController;
use Illuminate\Support\Facades\Validator;
use App\Models\GovernmentOrganization;
use Illuminate\Http\Request;

class GovernmentOrganizationController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $govtOrganization = GovernmentOrganization::where('status', 1)->with(['govtStaff', 'govtOrgCity', 'govtOrgCountry','govtOrgGroup'])->get();
        // return $govtOrganization;
        if ($request->expectsJson()) {
            try {
                return $this->sendResponse($govtOrganization, 'Government Organization Retrieved successfully' . ($govtOrganization->count() ? '.' : ', No Data Exist'), 'table');
            } catch (\Illuminate\Database\QueryException $exception) {
                return response()->json($exception->errorInfo[2], 400);
            }
        }

        return view('pages.govtOrganization');
        // return view('pages.govtOrganization', ['govtOrganization' => $govtOrganization]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.addGovtOrganization');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:30',
            'address' => 'sometimes|nullable|string|max:255',
            'country' => 'required|string|max:30',
            'city' => 'required|string|max:30',
            'group' => 'required|string|max:20',
            'allowed_quantity' => 'required|integer|max:200',
            'status' => 'integer|max:1',
            'ref_no' => 'sometimes|nullable|string|regex:/^\d{4,11}$/',
            'head_name' => 'sometimes|nullable|string|max:30',
            'head_email' => 'sometimes|nullable|string|max:30',
            'head_contact' => 'sometimes|nullable|string|regex:/^\d{11,20}$/',
        ]);

        // return $validator->validated();
        try {
            $govtOrganization = GovernmentOrganization::create($validator->validated());
            return $this->sendResponse($govtOrganization, 'Government Organization Created successfully.');
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
    public function show(Request $request)
    {
        // return view('pages.addGovtOrganization');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $uid)
    {
        $govtOrganization = GovernmentOrganization::where('status', 1)->where('uid', $uid)->with(['govtOrgCity', 'govtOrgCountry'])->first();
        // return $govtOrganization;
        return view('pages.addGovtOrganization', ['organization' => $govtOrganization]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req, string $uid)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'sometimes|string|max:30',
            'address' => 'sometimes|max:255',
            'country' => 'sometimes|string|max:30',
            'city' => 'sometimes|string|max:30',
            'group' => 'sometimes|string|max:20',
            'allowed_quantity' => 'sometimes|integer|max:200',
            'head_name' => 'sometimes|string|max:30',
            'head_email' => 'sometimes|string|max:30',
            'head_contact' => 'sometimes|string|regex:/^\d{11,20}$/',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        try {
            $govtOrganization = GovernmentOrganization::where('uid', $uid)->first();
            $govtOrganization->update($validator->validated());
            return $this->sendResponse($govtOrganization, 'Government Organization Updated successfully.');
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
            $govtOrganization = GovernmentOrganization::where('uid', $uuid)->where('status', 1)->update(['status' => 0]);
            return $this->sendResponse($govtOrganization, 'Government Organization Deleted successfully.');
        } catch (\Illuminate\Database\QueryException $ex) {
            // Handle specific database errors
            if ($ex->getCode() == 23000) { // Duplicate entry error (unique constraint violation)
                return $this->sendError('Database Error: Something Went Wrong.', $ex->getMessage(), 422);
            }
            // General database error
            return $this->sendError('Database Error.', $ex->getMessage());
        }
    }
}
