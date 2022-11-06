<?php

namespace App\Http\Controllers;

use App\Models\ProcurementCategories;
use App\Models\ProcurementMethods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\User;
use Inertia\Inertia;
use App\Models\ProcurementPlan;
use App\Models\ProcurementPlanDetails;
use App\Models\CompanyApprovalProcess;
use App\Models\CompanyApprovalOrder;
use App\Models\ApprovalRequestStatus;
use App\Traits\LogActivityTrait;
use App\Notifications\SendApprovalEmail;

class ProcurementPlanController extends Controller
{

    use LogActivityTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {

        for($i=@date('Y')+1; $i>(@date('Y') - 5); $i--)
		{
			$financialyr[] = $i.'-'.($i+1);
		}

        $userID = Auth::user()->id;
        $user = User::where('id',  $userID )->first();

        //Get all Plans

        $plans = ProcurementPlan::where('organization_id',
        Auth::user()->company_id)
        ->orWhere('organization_id', Auth::user()->id)
        ->orderBy('financial_year_end', 'desc')->get();


        return Inertia::render('ProcurementPlans/ProcurementPlan', [
            'user' => $user,
            'financialyr' => $financialyr,
            'plans' => $plans,
        ]);
    }

    public function updateCompanyProcurementApprovers(Request $request) {
        CompanyApprovalOrder::select('id', 'company_id' )->where('company_id', $this->getCompanyID())->delete();
        $approvers  = $request->approvers;
        $stepid = 1;

        foreach ($approvers as $key => $value) {
            CompanyApprovalOrder::create([
                'company_id' => $this->getCompanyID(),
                'module' => 'ProcurementPlanDetails',
                'user_step' => $stepid,
                'user_id' => $value["approver_user_id"],
                'created_by' =>  $this->getCompanyID()
            ]);

            $stepid++;
        }

        return response()->json(['success' => true, 'message' => 'Successfully Updated Procurement Plan Detail Approvers'], 200);
    }

    public function createProcurementDetailsApprovers(Request $request) {

        $approvers  = $request->approvers;

        $approval_process = CompanyApprovalProcess::select('id', 'company_id', 'module', 'approval_steps', 'created_by' )
        ->where('company_id', '=', $this->getCompanyID())
        ->where('module', '=', 'ProcurementPlanDetails')->first();

        if(!empty($approval_process)){

            $approval_process->module = 'ProcurementPlanDetails';
            $approval_process->approval_steps = count($approvers);
            $approval_process->created_by = $this->getCompanyID();
            $approval_process->save();

            CompanyApprovalOrder::where('company_id', $this->getCompanyID())
            ->where('module', 'ProcurementPlanDetails')->delete();
            // ADD NEW APPROVERS
            $this->registerProcurementPlanDetailsApprovers($approvers);

		}else{
            // CREATE COMPANY APPROVAL PROCESS
            $this->saveCompanyApprovalProcess($approvers);
            // ADD APPROVER DETAILS
            $this->registerProcurementPlanDetailsApprovers($approvers);
        }

        return response()->json(['success' => true, 'message' => 'Successfully Added Procurement Plan Detail Approvers'], 200);
    }

    public function saveCompanyApprovalProcess($approvers) {
        CompanyApprovalProcess::create([
            'company_id' => $this->getCompanyID(),
            'module' => 'ProcurementPlanDetails',
            'approval_steps' => count($approvers),
            'created_by' => $this->getCompanyID(),
        ]);
    }

    public function registerProcurementPlanDetailsApprovers($approvers) {
        $stepid = 1;

        foreach ($approvers as $key => $value) {
            CompanyApprovalOrder::create([
                'company_id' => $this->getCompanyID(),
                'module' => 'ProcurementPlanDetails',
                'user_step' => $stepid,
                'user_id' => $value["approver_user_id"],
                'created_by' =>  $this->getCompanyID()
            ]);

            $stepid++;
        }
    }

    public function getCompanyID() {
        return Auth::user()->id;
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'period' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['isvalid'=>false,'errors'=>$validator->messages()]);
        }

        $first_year = explode("-", $request->period)[0];
        $second_year = explode("-", $request->period)[1];

        //Check if Organization has already a plan

        $first_year_date = $first_year.'-07-01';
        $second_year_date = $second_year.'-06-30';
        $companyID = Auth::user()->company_id;


        $CheckPlan = DB::table('procurement_plans')
        ->select('id')
        ->where('organization_id', $companyID)
        ->where('financial_year_start', $first_year_date)
        ->first();

        if(empty($CheckPlan)){
            ProcurementPlan::create([

                'title' => "Procurement Plan",
                'financial_year_start' => $first_year_date,
                'financial_year_end' => $second_year_date,
                'status' => $request->status,
                'created_by' => Auth::user()->id,
                'organization_id' => $companyID,
                'period' => $request->period,

              ]);

              return response()->json(['success' => true,
              'message' => 'Successfully Created A Plan'], 200);

               //Log Ledger Creattion
               $subject = 'Creating a Procurement Plan';
               $details = 'Created Plan: ' . $request->financialyr;
               $this->addToLog($request->ip(), $subject, $details);

        }else{

            $newerr = array(
                "exists" => "This Plan Already Exists!"
            );

            return response()->json(['isvalid'=>false, 'errors' => $newerr ]);
        }
    }


    public function updatePlan(Request $request)

    {

        $validator = Validator::make($request->all(), [
            'period' => 'required',
            'status' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['isvalid'=>false,'errors'=>$validator->messages()]);
        }

        $first_year = explode("-", $request->period)[0];
        $second_year = explode("-", $request->period)[1];

        //Check if Organization has already a plan

        $first_year_date = $first_year.'-07-01';

        $second_year_date = $second_year.'-06-30';

        $plan = ProcurementPlan::find($request->id);

        $plan->status = $request->status;
        $plan->updated_by = Auth::user()->id;
        $plan->save();

        return response()->json(['success' => true,
              'message' => 'Successfully Created A Plan'], 200);



    }

    public function details($id) {
        $user = User::where('id',  Auth::user()->id )->first();

        //Get all Plan Details
        $getDetails = DB::table('procurement_plan_details')
        ->select('procurement_plan_details.id',
        'procurement_plan_details.plan_id',
        'procurement_plans.financial_year_start',
        'procurement_plans.financial_year_end',
        'procurement_plans.title',
        'procurement_plan_details.category_id',
        'procurement_plan_details.method_id',
        'procurement_plan_details.amount',
        'procurement_categories.name as category_name',
        'procurement_methods.name as method_name',
        'procurement_plan_details.C',
        'procurement_plan_details.E',
        'procurement_plan_details.F',
        'procurement_plan_details.G',
        'procurement_plan_details.H',
        'procurement_plan_details.I',
        'procurement_plan_details.J',
        'procurement_plan_details.K',
        'procurement_plan_details.L',
        'procurement_plan_details.M',
        'procurement_plan_details.N',
        'procurement_plan_details.created_by',
        'procurement_plan_details.created_at',
        'procurement_plan_details.brief',
        'procurement_plan_details.submitted',
        'procurement_plan_details.total_steps',
        'procurement_plan_details.status',
        'users.firstName',
        'lastName',
        'procurement_plan_details.step')
        ->join('users','procurement_plan_details.created_by',  '=', 'users.id')
        ->leftJoin('procurement_plans', 'procurement_plan_details.plan_id', '=', 'procurement_plans.id')
        ->leftJoin('procurement_categories', 'procurement_plan_details.category_id', '=', 'procurement_categories.id')
        ->leftJoin('procurement_methods', 'procurement_plan_details.method_id', '=', 'procurement_methods.id')
        ->where('procurement_plan_details.plan_id', $id)
        ->orderBy('procurement_plan_details.created_at','desc')->get();

        //Get Categories
        $getCategories = ProcurementCategories::select('id', 'name')->get();

        //Get Methods
        $getMethods = ProcurementMethods::select('id', 'name')->get();

        $plan = ProcurementPlan::select('id', 'financial_year_start', 'financial_year_end' )->where('id', $id)->first();

        return Inertia::render('ProcurementPlans/ProcurementPlanDetails', [
            'user' => $user,
            'getMethods' => $getMethods,
            'getCategories' => $getCategories,
            'plan' => $plan,
            'getDetails' => $getDetails
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


    public function submitProcumentPlanDetailForApproval(Request $request , $id) {
        $procurement_plan_detail = ProcurementPlanDetails::select('id', 'submitted', 'total_steps', 'step')
        ->where('id', $id)->first();


	    $selfsteps = CompanyApprovalProcess::where('module', 'ProcurementPlanDetails')
        ->where('company_id', $this->getProcurementOfficerCompanyIdOrCompanyAdministrator())->first();

        $stepscount = $selfsteps->approval_steps;


	    $procurement_plan_detail->submitted = "Yes";
	    $procurement_plan_detail->total_steps = $stepscount;
	    $procurement_plan_detail->step = '1';
        $procurement_plan_detail->save();

	    for ($i = 1; $i <= $stepscount; $i++){
            $first_approver = CompanyApprovalOrder::select('id', 'user_id', 'module', 'company_id', 'user_step')
            ->where('user_step', $i)
            ->where('company_id', $this->getProcurementOfficerCompanyIdOrCompanyAdministrator())->first();

            $data = ApprovalRequestStatus::create([
                'procurement_plan_detail_id' => $procurement_plan_detail->id,
                'approver_id' => $first_approver->user_id,
                'module' => 'ProcurementPlanDetails',
                'user_id' => $first_approver->user_id
            ]);
		}

		// SEND APPROVAL EMAIL

        User::find($first_approver->user_id)
        ->notify(new SendApprovalEmail("Request for Procurement Details Approval From '".$this->getLoggedInUserNames()."'",
         "A staff Member has Sent a request to you to approve a Procurement Plan Detail"));

        $this->addToLog($request->ip(), 'Submit Procurement Detail For Approval', 'Successfully Submitted A Procurement Detail For Approval By '.$this->getLoggedInUserNames());

        return response()->json(['success' => true, 'message' => 'Successfully Submitted A Procurement Plan Detail For Approval'], 200);

    }

    public function getLoggedInUserNames() {
        return Auth::user()->firstName.' '.Auth::user()->lastName;
    }

    public function validateIfLoggedInUserIsApprover($step_id) {

        $approval_order_process = CompanyApprovalOrder::select('id', 'module', 'user_id', 'user_step')
        ->where('module', 'ProcurementPlanDetails')
        ->where('user_step', $step_id)
        ->first();

        return response()->json([
            'success' => true,
            'message' => $approval_order_process->user_id == $this->getLoggedUserId() ? true : false,
            'user_id' => $approval_order_process->user_id,
            'user_step' => $approval_order_process->user_step
        ]);

    }

    public function getLoggedUserId() {
        return Auth::user()->id;
    }

    public function approveProcurementPlanDetail(Request $request) {

        $validator = Validator::make($request->all(), [
            'reason' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //Get Steps for approval

        $selfsteps = CompanyApprovalProcess::where('module', 'ProcurementPlanDetails')
        ->where('company_id', $this->getProcurementOfficerCompanyIdOrCompanyAdministrator())->first();

        $stepscount = $selfsteps->approval_steps;

        $details =	ProcurementPlanDetails::select('id', 'step', 'status', 'created_by')->where('id', $request->id)->first();
        if($request->step != $stepscount){

                $updatereq = ApprovalRequestStatus::select('id', 'procurement_plan_detail_id', 'approver_id', 'status', 'reason')
                        ->where('procurement_plan_detail_id', $request->id)
                        ->where('approver_id', $request->user_id)
                        ->where('module', 'ProcurementPlanDetails')->first();
                $updatereq->reason = $request->reason;
                $updatereq->status = 1;
                $updatereq->save();

                $nextstep = (intval($request->step) + 1);

                $get_next_approver =  CompanyApprovalOrder::select('id', 'user_id', 'module', 'company_id', 'user_step')
                ->where('user_step', $nextstep)
                ->where('company_id', $this->getProcurementOfficerCompanyIdOrCompanyAdministrator())->first();

                User::find($get_next_approver->user_id)
                ->notify(new SendApprovalEmail("Request for Procurement Details Approval From '".$this->getLoggedInUserNames()."'",
                "A staff Member has Sent a request to you to approve a Procurement Plan Detail"));
                $this->addToLog($request->ip(), 'Approving Procurement Plan Detail', 'Successfully  Approved  A Procurement Plan Detail  By '.$this->getLoggedInUserNames());


                $details->step = $nextstep;
                $details->save();

            }else{


                $updatereq = ApprovalRequestStatus::select('id', 'procurement_plan_detail_id', 'approver_id', 'status', 'reason')
                ->where('procurement_plan_detail_id', $request->id)
                ->where('approver_id', $request->user_id)
                ->where('module', 'ProcurementPlanDetails')->first();

                $updatereq->reason = $request->reason;
                $updatereq->status = 1;
                $updatereq->save();


                $details->step = $request->step;
                $details->status = "approved";
                $details->save();

                User::find($details->created_by)
                ->notify(new SendApprovalEmail("Your Procurement Plan Detail Is Now Approved",
                "Your Timesheet Is Now Approved"));

               $this->addToLog($request->ip(), 'Approving Procurement Plan Detail', 'Approving Procurement Plan Detail', 'Successfully  Approved  A Procurement Plan Detail  By '.$this->getLoggedInUserNames());

            }

        return response()->json(['success' => true, 'message' => 'Successfully Approved  A Procurement Plan Detail'], 200);

    }


    public function rejectProcurementPlanDetail(Request $request){

        $validator = Validator::make($request->all(), [
            'reason' => 'required',
            'status' => 'required'
        ]);



        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

      $details =	ProcurementPlanDetails::select('id', 'step', 'status', 'created_by')->where('id', $request->id)->first();

      $updatereq = ApprovalRequestStatus::select('id', 'procurement_plan_detail_id', 'approver_id', 'status', 'reason')
      ->where('procurement_plan_detail_id', $request->id)
      ->where('approver_id', $request->user_id)
      ->where('module', 'ProcurementPlanDetails')->first();

      $updatereq->reason = $request->reason;
      $updatereq->status = 2;
      $updatereq->save();

      $emailBody = "Your Timesheet Has Been Rejected at Step ".$request->step." Reason ".$request->reason;
      $subject = "Timesheet Rejection Notice";

      User::find($details->created_by)
      ->notify(new SendApprovalEmail($subject,
      $emailBody));

      $details->step = $request->step;
      $details->status = "rejected";
      $details->reason = $request->reason;
      $details->save();

      $this->addToLog($request->ip(), 'Successfully Rejected A Procurement Detail By '.$this->getLoggedInUserNames());

      return response()->json(['success' => true, 'message' => 'Successfully A Procurement  A Procurement Plan Detail'], 200);

    }

    public function detailsadd($id)

    {

        $user = User::where('id',  Auth::user()->id )->first();

        //Get all Plan Details


        //Get Categories
        $getCategories = ProcurementCategories::select('id', 'name')->get();

        //Get Methods
        $getMethods = ProcurementMethods::select('id', 'name')->get();

        $plan = ProcurementPlan::select('id', 'financial_year_start', 'financial_year_end' )->where('id', $id)->first();

        return Inertia::render('ProcurementPlans/ProcurementPlanDetailsAdd', [
            'user' => $user,
            'getMethods' => $getMethods,
            'getCategories' => $getCategories,
            'plan' => $plan,
        ]);

    }

    public function detailsedit($id)

    {

        $user = User::where('id',  Auth::user()->id )->first();

        //Get all Plan Details

        $plan_detail = ProcurementPlanDetails::find($id);


        //Get Categories
        $getCategories = ProcurementCategories::select('id', 'name')->get();

        //Get Methods
        $getMethods = ProcurementMethods::select('id', 'name')->get();

        $plan = ProcurementPlan::select('id', 'financial_year_start', 'financial_year_end' )->where('id', $plan_detail->plan_id)->first();

        return Inertia::render('ProcurementPlans/ProcurementPlanDetailsAdd', [
            'user' => $user,
            'getMethods' => $getMethods,
            'getCategories' => $getCategories,
            'plan' => $plan,
            'plan_detail' => $plan_detail,
        ]);

    }

    public function detailstore(Request $request)

    {

        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'method_id' => 'required',
            'amount' => 'required',
            'brief' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['isvalid'=>false,'errors'=>$validator->messages()]);
        }


        ProcurementPlanDetails::create([
            'plan_id' => $request->plan_id,
            'category_id' => $request->category_id,
            'method_id' => $request->method_id,
            'amount' => $request->amount,
            'brief' => $request->brief,
            'C' => $request->C,
            'E' => $request->E,
            'F' => $request->F,
            'G' => $request->G,
            'H' => $request->H,
            'I' => $request->I,
            'J' => $request->J,
            'K' => $request->K,
            'L' => $request->L,
            'M' => $request->M,
            'N' => $request->N,
            'created_by' => Auth::user()->id,

          ]);

          $subject = 'Creating a Procurement Plan';
          $details = 'Created Plan: ';
          $this->addToLog($request->ip(),  $subject, $details);

          return response()->json(['success' => true,
          'message' => 'Successfully Created Plan Details'], 200);
    }


    public function updateDetails(Request $request)

    {

        //return $request->plan_id;


        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'method_id' => 'required',
            'amount' => 'required',
            'brief' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['isvalid'=>false,'errors'=>$validator->messages()]);
        }


       $detail =  ProcurementPlanDetails::find($request->id);

       $detail->category_id = $request->category_id;
       $detail->method_id = $request->method_id;
       $detail->amount = $request->amount;
       $detail->brief = $request->brief;
       $detail->C = $request->C;
       $detail->E = $request->E;
       $detail->F = $request->F;
       $detail->G = $request->G;
       $detail->H = $request->H;
       $detail->I = $request->I;
       $detail->J = $request->J;
       $detail->K = $request->K;
       $detail->L = $request->L;
       $detail->M = $request->M;
       $detail->N = $request->N;
       $detail->updated_by = Auth::user()->id;
       $detail->save();

        $subject = 'Creating a Procurement Plan';
        $details = 'Created Plan: ';
        $this->addToLog($request->ip(), $subject, $details);


          return response()->json(['success' => true,
          'message' => 'Successfully Updated Plan Details'], 200);

    }

    public function add(){

        for($i=@date('Y')+1; $i>(@date('Y') - 5); $i--)
		{
			$financialyr[] = $i.'-'.($i+1);
		}


        $userID = Auth::user()->id;
        $user = User::where('id',  $userID )->first();

        //Get all Plans

        return Inertia::render('ProcurementPlans/ProcurementPlanAdd', [
            'user' => $user,
            'financialyr' => $financialyr
        ]);
    }

    public function edit($id){

        for($i=@date('Y')+1; $i>(@date('Y') - 5); $i--)
		{
			$financialyr[] = $i.'-'.($i+1);
		}


        $userID = Auth::user()->id;
        $user = User::where('id',  $userID )->first();
        $plan = ProcurementPlan::find($id);

        //Get all Plans

        return Inertia::render('ProcurementPlans/ProcurementPlanAdd', [
            'user' => $user,
            'financialyr' => $financialyr,
            'plan' => $plan
        ]);
    }

}
