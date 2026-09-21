<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseApiController as BaseApiController;
use App\Models\Event;
use App\Models\EventSession;
use App\Models\Visitors;
use App\Http\Controllers\Controller;
use App\Services\EmailService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class VisitorsController extends BaseApiController
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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Visitors::with(['stallsDay1', 'stallsDay2', 'eventSessions'])->get();

        return $data;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    public function getDataCnic(Request $request)
    {
        $identity = $request->input('searchIdentity');

        $visitor = Visitors::where('identity', $identity)->latest('updated_at')->first();

        if ($visitor) {
            return response()->json([
                'success' => true,
                'data' => $visitor,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Visitor not found',
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, EmailService $emailService)
    {
        // return 'Request recieved from api';
        // return $request->all();

        try {

            if (!isset($request->code) || strlen($request->code) < 6) {
                $request['code'] = $this->badge(6, 'TVFA');
            };

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:50',
                'designation' => 'required|string',
                'nationality' => 'required|string',
                'identity' => 'required|string|max:15',
                'contact' => 'nullable|string|max:13',
                'sector' => 'required|string|max:255',
                'company' => 'nullable|string',
                'code' => 'nullable|string|unique:visitors,code',
                'email' => 'nullable|email',
                'event' => 'nullable|string',
                'attandeePMDC' => 'nullable|string',
                'sessions' => 'nullable|array',
                'sessions.*' => 'integer',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }

            $validated = $validator->validated();
            $selectedSessionIds = $validated['sessions'] ?? [];
            unset($validated['sessions']);

            if (!empty($selectedSessionIds)) {
                $event = Event::where('name', $validated['event'] ?? null)
                    ->where('deleted', 0)
                    ->first();

                if (!$event) {
                    return $this->sendError('Validation Error.', ['event' => ['Selected event does not exist.']], 422);
                }

                $validSessionIds = EventSession::where('event_id', $event->id)
                    ->whereIn('id', $selectedSessionIds)
                    ->pluck('id')
                    ->all();

                if (count($validSessionIds) !== count(array_unique($selectedSessionIds))) {
                    return $this->sendError('Validation Error.', ['sessions' => ['One or more selected sessions are invalid.']], 422);
                }
            }

            $visitors = Visitors::create($validated);
            $visitors->eventSessions()->sync($selectedSessionIds);

            $emailService->sendAsync($visitors->email, $visitors);
            return $this->sendResponse($visitors, 'Visitor created successfully');
        } catch (\Illuminate\Database\QueryException $ex) {
            // Handle specific database errors
            if ($ex->getCode() == 23000) { // Duplicate entry error (unique constraint violation)
                return $this->sendError('Duplicate Error: ' . $ex->errorInfo[2], $ex->errorInfo[2], 422);
            }
            // General database error
            return $this->sendError('Database Error.', $ex->errorInfo[2]);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(Visitors $visitors)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Visitors $visitors)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Visitors $visitors)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visitors $visitors)
    {
        //
    }
}
