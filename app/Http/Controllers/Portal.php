<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Portal extends Controller
{
    public function index()
    {
        return view('portal.index');
    }
}
