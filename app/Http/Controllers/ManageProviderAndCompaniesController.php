<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;


class ManageProviderAndCompaniesController extends Controller
{
    public function __construct() {
       $this->middleware('auth');
    }

    public function index() {
        return Inertia::render('Companies/Companies');
    }

}
