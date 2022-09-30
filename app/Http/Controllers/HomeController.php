<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\SecretQuestion;
use App\Models\BidsInvitations;
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


    public function viewBidsInvitationsByProviders() {
        $current_date = date('Y-m-d');
        $bids = BidsInvitations::select('tender_notices.*',
        'procurement_plans.title',
        'procurement_plans.financial_year_start',
        'procurement_plans.financial_year_end', 
        'procurement_categories.name as category_name',
        'procurement_methods.name as method_name', 'users.organisationName')
        ->join('procurement_plans','procurement_plans.id','tender_notices.plan_id')
        ->leftJoin('users', 'procurement_plans.organization_id', '=', 'users.id')
        ->leftJoin('procurement_categories', 'tender_notices.category_id', '=', 'procurement_categories.id')
        ->leftJoin('procurement_methods', 'tender_notices.method_id', '=', 'procurement_methods.id')
        // ->whereDate('tender_notices.display_start_date', '>=', $current_date)
        // ->whereDate('tender_notices.display_end_date', '<=', $current_date)
        ->orderBy('tender_notices.created_at', 'desc')->get();
        return Inertia::render('Bids/ProviderBidInvitationsComponent', [
            'bids' => $bids
        ]);
    }

    public function submitBid($id) {
        $bid = BidsInvitations::select('tender_notices.*',
        'procurement_plans.title',
        'procurement_plans.financial_year_start',
        'procurement_plans.financial_year_end', 'procurement_categories.name as category_name',
        'procurement_methods.name as method_name')
        ->join('procurement_plans','procurement_plans.id','tender_notices.plan_id')
        ->leftJoin('users', 'procurement_plans.organization_id', '=', 'users.id')
        ->leftJoin('procurement_categories', 'tender_notices.category_id', '=', 'procurement_categories.id')
        ->leftJoin('procurement_methods', 'tender_notices.method_id', '=', 'procurement_methods.id')
        ->where('tender_notices.id', base64_decode($id))->first();
        return Inertia::render('Bids/SubmitBidComponent', [
            'bid' => $bid
        ]);
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
