<?php

namespace App\Http\Controllers;

// Get our helper functions
require_once("inc/functions.php");

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use App\Settings;
use Auth;
use Illuminate\Support\Facades\Redirect;
use Response;
use Session;

class InstallationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('installation');
    }

}
