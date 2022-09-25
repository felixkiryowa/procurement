<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcurementCategories;
use App\Models\ProcurementMethods;
use App\Models\BidsInvitations;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\User;
use Inertia\Inertia;
use App\Models\ProcurementPlan;
use App\Models\ProcurementPlanDetails;
use App\Traits\LogActivityTrait;
use DB;


class BidsInvitationsController extends Controller
{

    use LogActivityTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index() {


        $userID = Auth::user()->id;
        $user = User::where('id',  $userID )->first();

        //Get all Plans

        $plans = ProcurementPlan::where('organization_id', Auth::user()->company_id)->orderBy('financial_year_start', 'desc')->get();

        //Get all Plan Details
        $plan_details = ProcurementPlanDetails::where('plan_id', 2)->get();


        //Get all Invitations

        $bids = BidsInvitations::join('procurement_plans','procurement_plans.id','tender_notices.plan_id')
        ->select('tender_notices.*','procurement_plans.financial_year_start','procurement_plans.financial_year_end')
        ->where('procurement_plans.organization_id', Auth::user()->company_id)->orderBy('tender_notices.created_at', 'desc')->get();


        return Inertia::render('Bids/BidInvitationsComponent', [
            'user' => $user,
            'bids' => $bids,
            'plans' => $plans,
            'plan_details' => $plan_details,
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



}
