<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\SecretQuestion;
use App\Models\BidsInvitations;
use App\Models\SubmittedBid;
use App\Models\SubmittedBidDoc;
use App\Models\CompanyApprovalOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        ->where('tender_notices.status', 'published')
        ->orderBy('tender_notices.created_at', 'desc')->get();
        return Inertia::render('Bids/ProviderBidInvitationsComponent', [
            'bids' => $bids
        ]);
    }

    public function getBidInviteDetails($id) {

        $bid = BidsInvitations::select('tender_notices.*',
        'procurement_plans.title',
        'procurement_plans.financial_year_start',
        'procurement_plans.financial_year_end', 
        'procurement_categories.name as category_name',
        'procurement_methods.name as method_name', 'users.organisationName')
        ->join('procurement_plans','procurement_plans.id','tender_notices.plan_id')
        ->leftJoin('users', 'procurement_plans.organization_id', '=', 'users.id')
        ->leftJoin('procurement_categories', 'tender_notices.category_id', '=', 'procurement_categories.id')
        ->leftJoin('procurement_methods', 'tender_notices.method_id', '=', 'procurement_methods.id')
        ->where('tender_notices.id', base64_decode($id))
        ->first();
        return Inertia::render('Bids/BidInvitationDetails', [
            'bid_details' => $bid
        ]);

    }

    public function viewAndEditSubmittedBid($id) {
      
        $check_submitted_bid = SubmittedBid::select('id', 'tender_notice_id', 'user_id', 'amount',
         'brief_description', 'start_date', 'end_date', 'currency', 'status')
        ->where('id', base64_decode($id))
        ->where('user_id', Auth::user()->id)
        ->first();

        $submitted_documents = SubmittedBidDoc::select('id', 'submitted_bid_id', 'document', 'tracking_number')
        ->where('submitted_bid_docs.submitted_bid_id', $check_submitted_bid->id)->get();

        $bid = BidsInvitations::select('tender_notices.*',
        'procurement_plans.title',
        'procurement_plans.financial_year_start',
        'procurement_plans.financial_year_end', 'procurement_categories.name as category_name',
        'procurement_methods.name as method_name')
        ->join('procurement_plans','procurement_plans.id','tender_notices.plan_id')
        ->leftJoin('users', 'procurement_plans.organization_id', '=', 'users.id')
        ->leftJoin('procurement_categories', 'tender_notices.category_id', '=', 'procurement_categories.id')
        ->leftJoin('procurement_methods', 'tender_notices.method_id', '=', 'procurement_methods.id')
        ->where('tender_notices.id', $check_submitted_bid->tender_notice_id)->first();

        return Inertia::render('Bids/EditSubmittedBid', [
            'bid' => $bid,
            'submittted_bid' => $check_submitted_bid,
            'documents' => $submitted_documents
        ]);
    }


    public function checkIfBidIsAlreadySubmitted($id) {

        $check_submitted_bid = SubmittedBid::select('id', 'tender_notice_id', 'user_id')
        ->where('tender_notice_id', $id)
        ->where('user_id', Auth::user()->id)
        ->first();

        if($check_submitted_bid != NULL) {
            return response()->json(['success' => false], 200);
        }else {
            return response()->json(['success' => true], 200);

        }
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
            ->get(),
            'company_approval_orders' => CompanyApprovalOrder::select('id', 'company_id', 'module', 'user_step', 'user_id')
            ->where('company_id', $this->getProcurementOfficerCompanyIdOrCompanyAdministrator())
            ->orderBy('user_step', 'asc')
            ->get()
        ]);
    }

    public function getProcurementOfficerCompanyIdOrCompanyAdministrator() {
        $logged_in_user = User::select('id', 'user_role', 'company_id')
        ->where('id', Auth::user()->id)->first();

        if($logged_in_user->user_role == 'Procurement Officer') {

            $company_id = $logged_in_user->company_id;

        }else {

            $company_id = $logged_in_user->id;
        }

        return $company_id;
    }

    public function allUserSubmittedBids() {
        return Inertia::render('Bids/ProviderSubmittedBids', [
            'submitted_bids' => DB::table('submitted_bids')
            ->select('submitted_bids.id', 'submitted_bids.tender_notice_id', 'submitted_bids.amount', 'submitted_bids.brief_description',
             'submitted_bids.start_date', 'submitted_bids.end_date', 'submitted_bids.currency', 'submitted_bids.status',
              'submitted_bids.created_at', 'tender_notices.name', 'tender_notices.reference_number', 'users.organisationName' )
             ->leftJoin('tender_notices', 'submitted_bids.tender_notice_id', '=', 'tender_notices.id')
             ->leftJoin('procurement_plans', 'tender_notices.plan_id', '=', 'procurement_plans.id')
             ->leftJoin('users', 'procurement_plans.organization_id', '=', 'users.id')
            //  ->leftJoin('submitted_bid_docs', 'submitted_bid_docs.submitted_bid_id', '=', 'submitted_bids.id')
            ->where('submitted_bids.user_id', Auth::user()->id)
            ->get()
        ]);
    }

    public function providerSubmittedBidDetails($id) {
        return Inertia::render('Bids/ProviderSubmittedBidDetails', [
            'bid_details' => DB::table('submitted_bids')
            ->select('submitted_bids.id', 
             'submitted_bids.tender_notice_id', 
             'submitted_bids.amount', 
             'submitted_bids.brief_description',
             'submitted_bids.start_date', 
             'submitted_bids.end_date', 
             'submitted_bids.currency', 
             'submitted_bids.status',
             'submitted_bids.created_at', 
             'tender_notices.name', 
              'tender_notices.reference_number', 
              'users.organisationName', 'procurement_categories.name as category_name', 'procurement_methods.name as method_name'  )
             ->leftJoin('tender_notices', 'submitted_bids.tender_notice_id', '=', 'tender_notices.id')
             ->leftJoin('procurement_categories', 'tender_notices.category_id', '=', 'procurement_categories.id')
             ->leftJoin('procurement_methods', 'tender_notices.method_id', '=', 'procurement_methods.id')
             ->leftJoin('procurement_plans', 'tender_notices.plan_id', '=', 'procurement_plans.id')
             ->leftJoin('users', 'procurement_plans.organization_id', '=', 'users.id')
            ->where('submitted_bids.id', base64_decode($id))
            ->first()
        ]);
    }
}
