<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class TextController extends Controller
{
    public function getText(Request $request){
        return \App\Text::where('slug', $request->slug)->first();
    }
}
