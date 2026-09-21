<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseApiController as BaseApiController;
use Illuminate\Support\Facades\Validator;
use App\Models\StaffCategory;
use Illuminate\Http\Request;

class StaffCategoryController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = StaffCategory::all();
            return $this->sendResponse($categories, 'Categories Retrieved successfully' . ($categories->count() ? '.' : ', No Data Exist'), 'table');
        } catch (\Illuminate\Database\QueryException $exception) {
            return response()->json($exception->errorInfo[2], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
        ]);

        try {
            $categories = StaffCategory::create($validator->validated());
            return $this->sendResponse($categories, 'Staff Category Created successfully.');
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
     * Update the specified resource in storage.
     */
    public function update(Request $req, string $id)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string|max:255',
            'description' => 'sometimes|string|max:255',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        try {
            $staffCategory = StaffCategory::where('id', $id)->first();
            $staffCategory->update($validator->validated());
            return $this->sendResponse($staffCategory, 'Staff Category Updated successfully.');
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
    public function destroy(Request $req, string $id)
    {
        try {
            $categories = StaffCategory::where('id', $id)->delete();
            return $this->sendResponse($categories, 'Staff Category Deleted successfully.');
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
