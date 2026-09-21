<?php

namespace App\Http\Controllers;

use App\Models\Coupons;
use App\Models\Program;
use Illuminate\Http\Request;

class SnSeaController extends Controller
{

    public function getProgramStats()
    {

        $programs = Program::where('status', 1)->withCount('staff')->get();
        return $programs;
    }

    public function getCouponStats()
    {

        $coupons = Coupons::where('status', 1)->withCount('staff')->get();
        return $coupons;
    }

    public function render()
    {
        return view('pages.snseaDashboard');
    }
}
