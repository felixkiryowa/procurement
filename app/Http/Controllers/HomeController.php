<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\SecretQuestion;
use Illuminate\Support\Facades\Auth;

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
        return Inertia::render('Companies/ManageCompanies',[
            'companies' => User::select('id',
            'account_type_id',
            'organisationName',
            'procurementCategory',
            'briefDescription',
            'userName',
            'email',
            'companyPhoneNumber',
            'alternativeCompanyPhoneNumber',
            'secretQuestion',
            'secretAnswer',
            'challengeAnswer',
            'country',
            'registrationNumber',
            'taxId',
            'address',
            'status',
            'city',
            'originCountry',
            'region',
            'created_at',
            'zip_code')->where('account_type_id', 2)->get()
        ]
    );
    }

    public function viewManageProvider() {
        return Inertia::render('Companies/ManageProvidersComponent', [
            'providers' => User::select('id',
            'account_type_id',
            'organisationName',
            'procurementCategory',
            'briefDescription',
            'userName',
            'email',
            'companyPhoneNumber',
            'alternativeCompanyPhoneNumber',
            'secretQuestion',
            'secretAnswer',
            'challengeAnswer',
            'country',
            'registrationNumber',
            'taxId',
            'address',
            'status',
            'city',
            'originCountry',
            'region',
            'created_at',
            'zip_code')->where('account_type_id', 1)->get()
        ]);
    }

    public function viewManageCompanyUsers($id) {
        return Inertia::render('Companies/ManageCompanyUserComponent',[
            'users' => User::select('id',
            'userName',
            'organisationName',
            'company_id',
            'email',
            'firstName',
            'lastName',
            'created_at',
            'status',
            'user_role')
            ->where('company_id', base64_decode($id))
            ->where('user_role', '!=', 'Super Systems Administrator')
            ->get() ,
            'company' => User::select('id', 'organisationName')->where('id', base64_decode($id))->first()
        ]);
    }


    public function providerOrCompanyDetails($id) {
        return Inertia::render('Companies/DetailsViewComponent',[
            'selected_user' => User::select('id',
            'organisationName',
            'procurementCategory',
            'briefDescription',
            'userName',
            'email',
            'companyPhoneNumber',
            'alternativeCompanyPhoneNumber',
            'secretQuestion',
            'secretAnswer',
            'challengeAnswer',
            'country',
            'registrationNumber',
            'taxId',
            'firstName',
            'lastName',
            'address',
            'status',
            'city',
            'originCountry',
            'region',
            'user_role',
            'created_at',
            'zip_code')->where('id', base64_decode($id))->first()
        ]);
    }

    public function editCompanyProfile() {
        return Inertia::render('Users/EditProfileAndCompany', [
            'secret_questions' => SecretQuestion::select('id', 'name')->get(),
            'selected_user' => User::select(
                'id',
                'organisationName',
                'procurementCategory',
                'briefDescription',
                'userName',
                'password',
                'email',
                'companyPhoneNumber',
                'alternativeCompanyPhoneNumber',
                'secretQuestion',
                'secretAnswer',
                'challengeAnswer',
                'country',
                'registrationNumber',
                'taxId',
                'codeSentToEmail',
                'firstName',
                'lastName',
                'address',
                'city',
                'originCountry',
                'region',
                'zip_code',
                'user_role'
            )->where('id', Auth::user()->id)->first(),
            'procurement_plan_approvers' => User::select('id', 'firstName', 'lastName',
            'company_id')
            ->where('company_id', Auth::user()->id)
            ->orWhere('id', Auth::user()->id)
            ->orderBy('firstName', 'desc')
            ->get()
        ]);
    }
}
