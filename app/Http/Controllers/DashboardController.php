<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Only authenticated users can access
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Show the dashboard
    public function index()
    {
        return view('dashboard'); // Make sure you create this Blade file
    }
}
