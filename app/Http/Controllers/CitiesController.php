<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseApiController as BaseApiController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Cities;

class CitiesController extends BaseApiController
{
    public function create(Request $req)
    {
        $cities = new Cities();
        $cities->name = $req->name;
        $cities->description = $req->description;
        $citiesSaved = $cities->save();
        try {
            $citiesSaved = $cities->save();
            return $citiesSaved ? response()->json('City Added Successfully', 200) : response()->json('Something Went Wrong', 200);
            // return $citiesSaved ? redirect()->route('pages.programs')->with('message', 'City Added Successfully') : redirect()->route('pages.programs')->with('error', 'Something Went Wrong');
        } catch (\Illuminate\Database\QueryException $exception) {
            return response()->json($exception->errorInfo[2], 400);
        }
    }
    public function read(Request $req, $name = null)
    {
        $cities = $name ? Cities::where('name', $name)->get() : Cities::all();
        // return response()->json($countries, 200);
        return $cities;
    }
    public function update(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string|max:255',
            'description' => 'sometimes|string|max:255',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        try {
            $city = Cities::where('id', $id)->first();
            $city->update($validator->validated());
            return $this->sendResponse($city, 'City Updated successfully.');
        } catch (\Illuminate\Database\QueryException $ex) {
            // Handle specific database errors
            if ($ex->getCode() == 23000) { // Duplicate entry error (unique constraint violation)
                return $this->sendError('Database Error: Something Went Wrong.', $ex->getMessage(), 422);
            }
            // General database error
            return $this->sendError('Database Error.', $ex->getMessage());
        }
    }
    public function delete(Request $req)
    {

        try {
            $deleteCities = Cities::where('id', $req->id)->delete();
            return $this->sendResponse($deleteCities, 'City Deleted successfully.');
            return response()->json("City Deleted Successfully", 200);
            // return  redirect()->route('pages.essentials')->with('message', 'City Deleted Successfully');
        } catch (\Illuminate\Database\QueryException $exception) {
            return response()->json($exception->errorInfo[2], 400);
            // return redirect()->route('pages.programs')->with('error', $exception->errorInfo[2]);
        }
    }
}
