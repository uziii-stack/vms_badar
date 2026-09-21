<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EssentialsController extends Controller
{
    public function render()
    {
        return view('pages.essentials');
    }

    public function addCountryPage()
    {
        return view('pages.addCountry');
    }

    public function addCityPage()
    {
        return view('pages.addCity');
    }

    public function addGroupPage()
    {
        return view('pages.addGroup');
    }
}
