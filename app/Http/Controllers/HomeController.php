<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }


    public function viewBids() {
        return Inertia::render('Bids/BidComponent');
    }


    public function viewManageCompanies() {
        return Inertia::render('Companies/ManageCompanies');
    }

    public function viewManageProvider() {
        return Inertia::render('Companies/ManageProvidersComponent');
    }

    public function viewManageCompanyUsers() {
        return Inertia::render('Companies/ManageCompanyUserComponent');
    }
}
