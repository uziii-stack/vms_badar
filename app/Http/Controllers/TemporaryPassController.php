<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseApiController as BaseApiController;
use App\Models\TemporaryPass;
use Illuminate\Http\Request;

class TemporaryPassController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     */

    public function getData()
    {
        try {
            $temporaryPass = TemporaryPass::all();
            return $temporaryPass;
        } catch (\Illuminate\Database\QueryException $exception) {
            return response()->json($exception->errorInfo[2], 400);
        }
    }

    public function index()
    {
        return view('pages.temporaryPass');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TemporaryPass $temporaryPass)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TemporaryPass $temporaryPass)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // return $request;
        $validated = $request->all();
        $temporaryStaff = TemporaryPass::where('id', $id)->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'Temporary pass updated successfully.',
            'data' => TemporaryPass::find($id)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        // Find the record
        $temporaryStaff = TemporaryPass::find($id);

        // If not found, return 404
        if (!$temporaryStaff) {
            return response()->json([
                'success' => false,
                'message' => 'Temporary pass not found.'
            ], 404);
        }

        // Delete the record
        $temporaryStaff->delete();

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Temporary pass deleted successfully.',
            'data' => $temporaryStaff // returning deleted record info (optional)
        ]);
    }
}
