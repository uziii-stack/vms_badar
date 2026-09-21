<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Visitors;
use Illuminate\Http\Request;

class AttandeeController extends Controller
{
    // public function visitorsData()
    // {
    //     $data = Visitors::all();
    //     return $data;
    // }

    public function render()
    {
        $events = Event::active()->orderBy('start_date', 'desc')->get();

        return view('pages.attandee', compact('events'));
    }
}
