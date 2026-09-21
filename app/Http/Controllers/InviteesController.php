<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseApiController as BaseApiController;
use Illuminate\Support\Facades\Validator;
use App\Models\Invitees;
use Illuminate\Http\Request;

class InviteesController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $invitees = Invitees::where('status', 1)->with('rank')->get();
            return $this->sendResponse($invitees, 'Invitees Retrieved successfully' . ($invitees->count() ? '.' : ', No Data Exist'), 'table');
        } catch (\Illuminate\Database\QueryException $exception) {
            return response()->json($exception->errorInfo[2], 400);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.addInvitees');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string|max:255',
            'ranks_uid' => 'required|string|max:36',
            'designation' => 'required|string|max:255',
        ]);

        try {
            $coupon = Invitees::create($validator->validated());
            return $this->sendResponse($coupon, 'Coupon Created successfully.');
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
    public function show(Invitees $invitees)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, ?string $id = null)
    {
        // return $id;
        $invitee = Invitees::where('status', 1)->where('uid', $id)->with('rank')->first();
        // return $invitee;
        return view('pages.addInvitees', ['invitee' => $invitee]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req, ?string $id = null)
    {
         $validator = Validator::make($req->all(), [
            'name' => 'sometimes|string|max:255',
            'ranks_uid' => 'sometimes|string|max:36',
            'designation' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        try {
            $invitee = Invitees::where('uid', $id)->first();
            $invitee->update($validator->validated());
            return $this->sendResponse($invitee, 'Invitee Updated successfully.');
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
    public function destroy(Invitees $invitees)
    {
        //
    }
}
