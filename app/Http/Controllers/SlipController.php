<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{TemporaryPass, Visitors};
use Illuminate\Http\Request;

class SlipController extends Controller
{
    public function render(Request $req, $identity)
    {
        $visitors = Visitors::where('identity', $identity)->get();
        return view('pages.slips', ['data' => $visitors]);
    }

    public function temporarySlipRender(Request $req, $id)
    {
        $visitors = TemporaryPass::where('id', $id)->orWhere('identity', $id)->get();
        foreach ($visitors as $key => $visitor) {
            $visitors[$key]->deadline = "02-Nov-25";
        }
        return view('pages.slips', ['data' => $visitors]);
    }
}
