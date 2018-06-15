<?php

namespace App\Http\Controllers\ROLE;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ROLEController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:role');
    }

    /**
     * Show ROLE's dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        return view('role.dashboard');
    }
}
