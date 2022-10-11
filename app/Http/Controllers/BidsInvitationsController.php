<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcurementCategories;
use App\Models\ProcurementMethods;
use App\Models\BidsInvitations;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\User;
use Inertia\Inertia;
use App\Models\ProcurementPlan;
use App\Models\SubmittedBid;
use App\Models\SubmittedBidDoc;
use App\Models\ProcurementPlanDetails;
use App\Traits\LogActivityTrait;


class BidsInvitationsController extends Controller
{

    use LogActivityTrait;

    public function __construct()
    {
        $this->middleware('auth');
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

    public function getSubmittedBidDetails($submitted_bid_id) {
        $submitted_documents = SubmittedBidDoc::select('id', 'submitted_bid_id', 'document')
        ->where('submitted_bid_docs.submitted_bid_id', $submitted_bid_id)->get();

        return $submitted_documents;
    }

    public function dowloadUploadedDocumentFile(Request $request) {

        $selected_document = SubmittedBidDoc::select('id', 'document', 'tracking_number')
        ->where('id', $request->document_id)->first();

        if($request->tracking_number == $selected_document->tracking_number) {
            return response()->json(['success' => true, 
            'document_path' => 'bid_documents/'.$selected_document->document]);
        }else {
            return response()->json(['success' => false, 
            'message' => 'Invalid Tracking Number '.$request->tracking_number]);
        }
    }

    public function index() {
        //Get all Plans
        $plans = ProcurementPlan::where('organization_id',
         $this->getProcurementOfficerCompanyIdOrCompanyAdministrator())
         ->orderBy('financial_year_start', 'desc')->get();

        //Get all Plan Details
        $plan_details = ProcurementPlanDetails::where('plan_id', 1)->get();

        //Get all Invitations
        $bids = BidsInvitations::join('procurement_plans','procurement_plans.id','tender_notices.plan_id')
        ->select('tender_notices.*','procurement_plans.financial_year_start',
        'procurement_plans.financial_year_end')
        ->where('procurement_plans.organization_id', 
        $this->getProcurementOfficerCompanyIdOrCompanyAdministrator())
        ->orderBy('tender_notices.created_at', 'desc')->get();

        return Inertia::render('Bids/BidInvitationsComponent', [
            'bids' => $bids,
            'plans' => $plans,
            'plan_details' => $plan_details,
        ]);
    }

    public function getSubmittedBids($tender_notice_id) {
        return  DB::table('submitted_bids')
        ->select('submitted_bids.id', 
        'submitted_bids.tender_notice_id',
        'submitted_bids.user_id',
        'submitted_bids.amount',
        'submitted_bids.brief_description', 
        'submitted_bids.start_date',
        'submitted_bids.end_date', 
        'submitted_bids.currency', 
        'submitted_bids.status',
        'submitted_bids.created_at', 
        'users.firstName', 
        'users.lastName')
        ->leftJoin('users', 'submitted_bids.user_id', '=', 'users.id')
        ->where('submitted_bids.tender_notice_id', $tender_notice_id)->get();
    }

    public function showAllSubmittedBids() {
        $company_id = $this->getProcurementOfficerCompanyIdOrCompanyAdministrator();
        return Inertia::render('Bids/AllCompanySubmittedBids',[
           'all_submitted_bids' =>  DB::table('submitted_bids')
            ->select('submitted_bids.id', 
            'submitted_bids.tender_notice_id',
            'submitted_bids.user_id',
            'submitted_bids.amount',
            'submitted_bids.brief_description', 
            'submitted_bids.start_date',
            'submitted_bids.end_date', 
            'submitted_bids.currency', 
            'submitted_bids.status',
            'submitted_bids.created_at',
            'submitted_bids.updated_at', 
            'users.firstName', 
            'users.lastName',
            'users.organisationName',
            'procurement_plans.financial_year_start',
            'procurement_plans.financial_year_end',
            'procurement_plans.title',
            'procurement_plans.period')
            ->leftJoin('users', 'submitted_bids.user_id', '=', 'users.id')
            ->leftJoin('tender_notices', 'submitted_bids.tender_notice_id', '=', 'tender_notices.id')
            ->leftJoin('procurement_plans', 'tender_notices.plan_id', '=', 'procurement_plans.id')
            ->where('procurement_plans.organization_id', $company_id)
            ->where('submitted_bids.status', 'submitted')
            ->get()
        ]);
    }


    public function getProcurementDetails($id) {

        $pdetails = ProcurementPlanDetails::where('plan_id', $id)->get();

        return response()->json([
            'success' => true,
            'details' => $pdetails,
        ], 200);


    }

    public function store(Request $request)

    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required',
            'subject_id' => 'required',
            'reference_number' => 'required',
            'type' => 'required',
            'deadline' => 'required',
            'display_start_date' => 'required',
            'display_end_date' => 'required',
            'status' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['isvalid'=>false,'errors'=>$validator->messages()]);
        }

        //Get details

        $plan_details = ProcurementPlanDetails::where('id', $request->subject_id)->first();

        if(!empty($plan_details)){

            BidsInvitations::create([

            'plan_id' => $request->plan_id,
            'name' => $plan_details->brief,
            'subject_id' => $request->subject_id,
            'category_id' => $plan_details->category_id,
            'method_id' => $plan_details->method_id,
            'reference_number' => $request->reference_number,
            'details' => $request->details,
            'type' => $request->type,
            'deadline' => $request->deadline,
            'display_start_date' => $request->display_start_date,
            'display_end_date' => $request->display_end_date,
            'status' => $request->status,
            'created_by' => Auth::user()->id,
            'budget_amount' => $plan_details->amount,

            ]);

              return response()->json(['success' => true,
              'message' => 'Successfully Created Bid Invitation'], 200);

               //Log Ledger Creattion
               $subject = 'Creating a Bid Invitation';
               $details = 'Created Bid Invitation: ';
               $this->addToLog($subject, $details);

        }else{
            $newerr = array(
                "exists" => "This Plan Details Don't!"
            );

            return response()->json(['isvalid'=>false, 'errors' => $newerr ]);
        }

    }

    public function update(Request $request)

    {

        $validator = Validator::make($request->all(), [
            'plan_id' => 'required',
            'subject_id' => 'required',
            'reference_number' => 'required',
            'type' => 'required',
            'deadline' => 'required',
            'display_start_date' => 'required',
            'display_end_date' => 'required',
            'status' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['isvalid'=>false,'errors'=>$validator->messages()]);
        }

        $bid_invitation = BidsInvitations::find($request->id);

        $plan_details = ProcurementPlanDetails::where('id', $request->subject_id)->first();

        $bid_invitation->plan_id = $request->plan_id;
        $bid_invitation->name = $plan_details->brief;
        $bid_invitation->subject_id = $request->subject_id;
        $bid_invitation->category_id = $plan_details->category_id;
        $bid_invitation->method_id = $plan_details->method_id;
        $bid_invitation->reference_number = $request->reference_number;
        $bid_invitation->details = $request->details;
        $bid_invitation->type = $request->type;
        $bid_invitation->deadline = $request->deadline;
        $bid_invitation->display_start_date = $request->display_start_date;
        $bid_invitation->display_end_date = $request->display_end_date;
        $bid_invitation->status = $request->status;
        $bid_invitation->updated_by = Auth::user()->id;
        $bid_invitation->budget_amount = $plan_details->amount;
        $bid_invitation->save();
        
        return response()->json(['success' => true,
        'message' => 'Successfully Updated A Bid'], 200);
    }

    public function submitProviderBid(Request $request) {
        
        $submitted_bid = SubmittedBid::create([
            'tender_notice_id' => $request->tender_notice_id,
            'user_id' => Auth::user()->id, 
            'amount' => $request->amount, 
            'brief_description' => $request->brief_description, 
            'start_date' => $request->start_date, 
            'end_date' => $request->end_date, 
            'currency' => $request->currency, 
            'status' => $request->status
       ]);

       $this->saveUploadedFilesAtSubmittingBid($request, $submitted_bid->id);

        $subject = 'Submit BID';
        $details = 'Submit Bid for tender notice with ID : '.$request->tender_notice_id;
        $this->addToLog($request->ip(), $subject, $details);

        return response()->json(['success' => true,
        'message' => 'Successfully Submitted  A Bid'], 200);
    }

    public function saveUploadedFilesAtSubmittingBid($request, $submitted_bid_id) {
        if(count($request->uploaded_files) > 0) {
            foreach ($request->uploaded_files as $key => $value) {

                $file = explode(';', $value['file']);
                $application_part = $file[0];
                $extension = explode('/', $application_part);
    
                if($extension[1] == "pdf") {
                   $file_extension = ".pdf";
                }else {
                    $file_extension = ".doc";
                }

                $random_number = rand(100, 1000000);
    
                $base64_file = explode(',', $file[1]);
                $base64_decode = base64_decode($base64_file[1]);
                $generatedFile = fopen(public_path('bid_documents/'.'bid_doc'.date('Y-m-d').$key.$random_number.$file_extension), 'w');
                fwrite($generatedFile, $base64_decode);
                fclose($generatedFile);
                $fileName = 'bid_doc'.date('Y-m-d').$key.$random_number.$file_extension;
                SubmittedBidDoc::create([
                    'submitted_bid_id' => $submitted_bid_id,
                    'document' => $fileName,
                    'tracking_number' => time().$key,
                    'created_by' => Auth::user()->id,
                ]);
            }
        }
    }

    public function updateSubmittedBid(Request $request) {

        $submitted_bid = SubmittedBid::select('id', 'amount', 'brief_description', 
        'start_date', 'end_date', 'currency', 'status')->where('id', $request->id)->first();

        $submitted_bid->amount = $request->amount; 
        $submitted_bid->brief_description = $request->brief_description; 
        $submitted_bid->start_date = $request->start_date; 
        $submitted_bid->end_date = $request->end_date; 
        $submitted_bid->currency = $request->currency; 
        $submitted_bid->status = $request->status;
        $submitted_bid->save();

        $this->saveUploadedFilesAtSubmittingBid($request, $request->id);

        $subject = 'Editing Submitted  BID with ID '.$request->id;
        $details = 'Editing Submitted Bid  with ID  : '.$request->id;
        $this->addToLog($request->ip(), $subject, $details);

        return response()->json(['success' => true,
        'message' => 'Successfully Edited  A Bid'], 200);

    }

}
