<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseApiController as BaseApiController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Program;


class ProgramController extends BaseApiController
{
    public function render()
    {
        return view('pages.programs');
    }

    public function programsData()
    {
        $program = Program::get();
        return $program;
    }

    public function addProgramPages(Request $req, ?string $uid=null)
    {
        if (isset($uid)) {
            $program = Program::where('program_uid', $uid)->first();
            // return $program;
            return view("pages.addProgram", ['program' => $program]);
        }
        return view("pages.addProgram");
    }

    public function addProgram(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'program_name' => 'required|string|max:255',
            'program_day' => 'required|integer|max:4',
            'program_start_time' => 'required|string|max:2359',
            'program_end_time' => 'required|string|max:2359',
        ]);

        try {
            $program = Program::create($validator->validated());
            return $this->sendResponse($program, 'Program Created successfully.');
        } catch (\Illuminate\Database\QueryException $ex) {
            // Handle specific database errors
            if ($ex->getCode() == 23000) { // Duplicate entry error (unique constraint violation)
                return $this->sendError('Database Error: Something Went Wrong.', $ex->getMessage(), 422);
            }
            // General database error
            return $this->sendError('Database Error.', $ex->getMessage());
        }
    }

    public function updateProgram(Request $req, string $uid)
    {
        $validator = Validator::make($req->all(), [
            'program_name' => 'sometimes|string|max:255',
            'program_day' => 'sometimes|integer|max:4',
            'program_start_time' => 'sometimes|string|max:2359',
            'program_end_time' => 'sometimes|string|max:2359',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        try {
            $program = Program::where('program_uid', $uid)->first();
            $program->update($validator->validated());
            return $this->sendResponse($program, 'Program Updated successfully.');
        } catch (\Illuminate\Database\QueryException $ex) {
            // Handle specific database errors
            if ($ex->getCode() == 23000) { // Duplicate entry error (unique constraint violation)
                return $this->sendError('Database Error: Something Went Wrong.', $ex->getMessage(), 422);
            }
            // General database error
            return $this->sendError('Database Error.', $ex->getMessage());
        }
    }

    public function deleteProgram(Request $req)
    {
        try {
            $deleteProgram = Program::where('program_uid', $req->id)->delete();
            return $this->sendResponse($deleteProgram, 'Program Deleted successfully.');
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
