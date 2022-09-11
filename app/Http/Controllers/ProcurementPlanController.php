<?php

namespace App\Http\Controllers;

use App\Models\ProcurementCategories;
use App\Models\ProcurementMethods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\User;
use Inertia\Inertia;
use App\Models\ProcurementPlan;
use App\Models\ProcurementPlanDetails;
use App\Traits\LogActivityTrait;
use DB;



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

        $plans = ProcurementPlan::where('organization_id', Auth::user()->company_id)->orderBy('financial_year_end', 'desc')->get();


        return Inertia::render('ProcurementPlans/ProcurementPlan', [
            'user' => $user,
            'financialyr' => $financialyr,
            'plans' => $plans,
        ]);
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
               $this->addToLog($subject, $details);

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


        $userID = Auth::user()->id;
        $user = User::where('id',  $userID )->first();

        //Get all Plan Details

        $getDetails = ProcurementPlanDetails::where('plan_id', $id)->orderBy('created_at','desc')->get();

        //Get Categories

        $getCategories = ProcurementCategories::all();

        //Get Methods

        $getMethods = ProcurementMethods::all();

        $plan = ProcurementPlan::find($id);


        return Inertia::render('ProcurementPlans/ProcurementPlanDetails', [
            'user' => $user,
            'getMethods' => $getMethods,
            'getCategories' => $getCategories,
            'plan' => $plan,
            'getDetails' => $getDetails
        ]);
    }

    public function detailstore(Request $request)

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

          return response()->json(['success' => true,
          'message' => 'Successfully Created Plan Details'], 200);

           //Log Ledger Creattion
           $subject = 'Creating a Procurement Plan';
           $details = 'Created Plan: ';
           $this->addToLog($subject, $details);



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


          return response()->json(['success' => true,
          'message' => 'Successfully Updated Plan Details'], 200);

           //Log Ledger Creattion
           $subject = 'Creating a Procurement Plan';
           $details = 'Created Plan: ';
           $this->addToLog($subject, $details);



    }

}
