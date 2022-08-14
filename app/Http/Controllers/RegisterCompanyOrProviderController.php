<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class RegisterCompanyOrProviderController extends Controller
{
    
     public function index() {
        return Inertia::render('RegisterAsCompanyOrProvider/RegisterAsCompanyOrProvider');
     }
}
