<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth'); // user wajib login
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        // kirim data permission ke view
        return view('home', [
            'canCreateMapping' => auth()->check() && auth()->user()->can('create mapping'),
        ]);
    }
}
