<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Models\Ledger;
use App\Models\LedgerCategory;
use App\Traits\LogActivityTrait;
use Illuminate\Support\Facades\Validator;
use App\Exports\CashbookExport;
use App\Exports\ItemCodeExport;
use App\Models\GeneralLedger;
use Excel;
use Auth;


class ChartOfAccountsController extends Controller
{

    use LogActivityTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $ledgers = DB::table('ledgers')
        ->select('id', 'name',  'created_at')
        ->orderBy('ledgers.created_at', 'DESC')
        ->get();
        return Inertia::render('ChartOfAccounts/ChartOfAccountsComponent', [
            'ledgers' => $ledgers
        ]);
    }

    public function manageLedgerCategories() {
        $ledger_categories = DB::table('ledger_categories')
        ->select('ledger_categories.id as ledger_categories_id',
            'ledger_categories.name as category_name',
            'ledger_categories.item_code',
            'ledger_categories.ledger_id as account_group_id',
            'ledgers.name', 'ledger_categories.created_at', 'ledger_categories.item_code')
        ->leftJoin('ledgers', 'ledger_categories.ledger_id', '=', 'ledgers.id')
        ->orderBy('ledger_categories.created_at', 'DESC')
        ->get();

        $ledgers = DB::table('ledgers')
        ->select('id', 'name', 'item_code', 'created_at')
        ->orderBy('ledgers.created_at', 'DESC')
        ->get();

        return Inertia::render('ChartOfAccounts/LedgerCategoryComponent', [
            'ledger_categories' => $ledger_categories,
            'ledgers' => $ledgers
        ]);
    }

    public function loadAllLedgerCategories() {
        return  DB::table('ledger_categories')
        ->select('ledger_categories.id as ledger_categories_id',
            'ledger_categories.name as category_name',
            'ledger_categories.ledger_id as account_group_id',
            'ledgers.name', 'ledger_categories.created_at', 'ledger_categories.item_code')
        ->leftJoin('ledgers', 'ledger_categories.ledger_id', '=', 'ledgers.id')
        ->orderBy('ledger_categories.created_at', 'DESC')
        ->get();
    }

    public function createLedgerCategory(Request $request)
    {
        $messages = [
            'account_group_id.required' => 'The Account Group field is required',
            'category_name.required' => 'The Ledger Category Name field is required'
        ];

        $validator = Validator::make($request->all(), [
            'account_group_id' => 'required',
            'category_name' => 'required',
            'item_code' => 'required'
        ], $messages);

        //VALIDATE THE REQUEST INPUTS
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        //Get the last created category under an account group
        $account_group_category = DB::table('ledger_categories')
            ->latest('ledger_categories.created_at')
            ->where('ledger_categories.ledger_id', $request->account_group_id)->first();
        // //Get information about a given account group
        // $account_group = Ledger::select('id', 'name', 'item_code')
        // ->where('id', $request->account_group_id)->first();
        // if ($account_group_category) {
        //     //Get last two digits of item code
        //     $new_string = substr($account_group_category->item_code, -2);
        //     //convert string to integer
        //     $integer_string = (int) $new_string;

        //     if ($integer_string <= 9) {
        //         $new_last_digit = $integer_string + 1;
        //         $code = (string) $new_last_digit;
        //         $intact_code = '0' . $code;
        //         $new_item_code = $intact_code;
        //     } else {
        //         $new_last_digit = $integer_string + 1;
        //         $code = (string) $new_last_digit;
        //         $new_item_code = $code;
        //     }
            LedgerCategory::create([
                'ledger_id' => $request->account_group_id,
                'name' => str_replace("'", "''", trim(htmlspecialchars(ucwords($request->category_name)))),
                // 'item_code' => $account_group->item_code . '-' . $new_item_code,
                'item_code' => str_replace("'", "''", trim(htmlspecialchars(ucwords($request->item_code))))
            ]);

            //Log Ledger Creattion
            $subject = 'Creating a ledger category';
            $details = 'Created Category Name: ' . $request->category_name;
            $this->addToLog($subject, $details);

            return response()->json([
                'success' => true,
                'message' => 'Sucessfully created '.$request->category_name.' as a ledger category',
            ], 200);
        // } else {
            // $new_item_code = $account_group->item_code . '-01';
            LedgerCategory::create([
                'ledger_id' => $request->account_group_id,
                'name' => str_replace("'", "''", trim(htmlspecialchars(ucwords($request->category_name)))),
                'item_code' =>str_replace("'", "''", trim(htmlspecialchars(ucwords($request->item_code))))
            ]);

            //Log Ledger Creattion
            $subject = 'Creating a ledger category';
            $details = 'Created Category Name: ' . $request->category_name;
            $this->addToLog($subject, $details);

            return response()->json([
                'success' => true,
                'message' => 'Sucessfully created a ledger category'
            ], 200);
        //  }
    }


    public function updateLedgerCategory(Request $request) {
        $messages = [
            'account_group_id.required' => 'The Account Group field is required',
            'category_name.required' => 'The Ledger Category Name field is required'
        ];

        $validator = Validator::make($request->all(), [
            'account_group_id' => 'required',
            'category_name' => 'required',
        ], $messages);

        //VALIDATE THE REQUEST INPUTS
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        //Retrieve the Category and update
        $ledger = LedgerCategory::select('id', 'name', 'ledger_id', 'item_code')
        ->where('id', $request->ledger_categories_id)->first();
        $ledger->name = str_replace("'", "''", trim(htmlspecialchars(ucwords($request->category_name))));
        $ledger->ledger_id = $request->account_group_id;
        $ledger->item_code = $request->item_code;
        $ledger->save();
        //persist the data
        $subject = 'Updating a ledger category';
        $details = 'Updated  Category Name: ' . $request->category_name;
        $this->addToLog($subject, $details);

        return response()->json([
            'success' => true,
            'message' => 'Sucessfully updated a ledger category'
        ], 200);
    }

    //CLIENT STATEMENT
    public function AccountStatement()
    {
        //Get member accounts

        $all_accounts = array();
        $all_teller_accounts = array();
        $bank_accounts = DB::table('bank_accounts')->get();
        $sub_bank_accounts = DB::table('sub_bank_accounts')->get();
        $last_bank_accounts = DB::table('last_bank_accounts')->get();
        $more_account_belows = DB::table('more_account_belows')->get();


        foreach ($bank_accounts as $value) {
            $data1 = array(
                'name' => $value->name,
                'account_number' => $value->account_number,
            );
            array_push($all_accounts, $data1);
        }
        foreach ($sub_bank_accounts as $value) {
            $data2 = array(
                'name' => $value->name,
                'account_number' => $value->account_number,
            );
            array_push($all_accounts, $data2);
        }
        foreach ($last_bank_accounts as $value) {
            $data3 = array(
                'name' => $value->name,
                'account_number' => $value->account_number,
            );
            array_push($all_accounts, $data3);
        }

        foreach ($more_account_belows as $value) {
            $data4 = array(
                'name' => $value->name,
                'account_number' => $value->account_number,
            );
            array_push($all_accounts, $data4);
        }
        //return $all_accounts;
        $data = array(
            'all_accounts' => $all_accounts,
        );
        return view('chartofaccounts/accountstatement', [
            'all_accounts' => $all_accounts,
            'user' => Auth::user(),
        ]);
    }

    public function generateAccountStatement(Request $request)
    {
       // return $request->account_number;
        $action = $request->action;

        if($action == 'excel'){
           $start_date = $request->statement_start_date;
           $end_date = $request->statement_end_date;
           $account = $request->account_number;
        //     //return $company_id;
            $fileNameToDownload = 'cashbook'.'_'.time().'.'.'xlsx';

             return Excel::download(new   CashbookExport($start_date, $end_date, $account), $fileNameToDownload);



         }else{

        //Get Item accounts

        $all_accounts = array();
        $bank_accounts = DB::table('bank_accounts')->get();
        $sub_bank_accounts = DB::table('sub_bank_accounts')->get();
        $last_bank_accounts = DB::table('last_bank_accounts')->get();
        $more_account_belows = DB::table('more_account_belows')->get();


        foreach ($bank_accounts as $value) {
            $data1 = array(
                'name' => $value->name,
                'account_number' => $value->account_number,
            );
            array_push($all_accounts, $data1);
        }
        foreach ($sub_bank_accounts as $value) {
            $data2 = array(
                'name' => $value->name,
                'account_number' => $value->account_number,
            );
            array_push($all_accounts, $data2);
        }
        foreach ($last_bank_accounts as $value) {
            $data3 = array(
                'name' => $value->name,
                'account_number' => $value->account_number,
            );
            array_push($all_accounts, $data3);
        }

        foreach ($more_account_belows as $value) {
            $data4 = array(
                'name' => $value->name,
                'account_number' => $value->account_number,
            );
            array_push($all_accounts, $data4);
        }

        $early_journal_entries = DB::table('general_ledgers')
            ->select('general_ledgers.*')
            ->whereDate('general_ledgers.created_at', '<', $request->statement_start_date)
            ->where('general_ledgers.account', $request->account_number)
            ->get();

        $account_journal_entries = GeneralLedger::whereBetween('general_ledgers.created_at', [
                $request->statement_start_date, $request->statement_end_date,
        ])->where('general_ledgers.account', $request->account_number)
        ->get();

        $all_account_journal_entries = GeneralLedger::whereBetween('general_ledgers.created_at', [
            $request->statement_start_date, $request->statement_end_date,
        ])->where('general_ledgers.account', $request->account_number)
        ->get();

        $debits1 = 0;
        $credits1 = 0;
        $credits = 0;
        $debits = 0;

        foreach ($early_journal_entries as $entries) {
            if ($entries->debit != null) {
                $debits = $debits + $entries->debit;
            }
        }

        foreach ($early_journal_entries as $entries) {
            if ($entries->credit != null) {
                $credits = $credits + $entries->credit;
            }
        }

        foreach ($all_account_journal_entries as $entries1) {
            if ($entries1->debit != null) {
                $debits1 = $debits1 + $entries1->debit;
            }
        }

        foreach ($all_account_journal_entries as $entries1) {
            if ($entries1->credit != null) {
                $credits1 = $credits1 + $entries1->credit;
            }
        }


        $Prev_amount_on_account =  $debits - $credits;

        //return $debits1.'-'.$credits1.'-'.$Prev_amount_on_account;

        if($Prev_amount_on_account > $debits1){

            $amount_on_account =    $Prev_amount_on_account + $debits1 - $credits1;

        }else{

        $amount_on_account =  $debits1 - $credits1;

        }

        return view('chartofaccounts/accountstatementview',  [
            'account_journal_entries' => $account_journal_entries,
            'Prev_amount_on_account' => $Prev_amount_on_account,
            'amount_on_account' => $amount_on_account,
            'start_date' => $request->statement_start_date,
            'end_date' => $request->statement_end_date,
            'all_accounts' => $all_accounts,
            'account' => $request->account_number,
            'user' => Auth::user(),
        ]);
      }
    }

    public function entire_chart_of_accounts()
    {

        $account_group = Ledger::with(
            'ledger_categories',
            'ledger_categories.bank_accounts',
            'ledger_categories.bank_accounts.sub_bank_accounts',
            'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account',
            'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account.more_accounts_below'
        )->get();

        $data = array(
            'account_group' => $account_group,
            'user' => Auth::user()
        );
        return view('chartofaccounts.entirechartofaccounts',
            $data
        );

    }


    public function TrialbalanceView(Request $request)
    {

            // $this->trialbalance($request->report_start_date, $request->report_end_date);

            $assetscode = '3';
            $liabilitiescode = '4';
            $expensescode = '2';
            $revenuecode = '1';
            $equitycode = '5';





        return view('chartofaccounts/trialbalanceview',  [

                'user' => Auth::user(),

        ]);
    }

    public function Trialbalance(Request $request)
    {



        $assetscode = '3';
            $liabilitiescode = '4';
            $expensescode = '2';
            $revenuecode = '1';
            $equitycode = '5';
            $debit = 0;
            $credit = 0;

            //Get all assets from the GL

            $account_group_assets = Ledger::with(
                'ledger_categories',
                'ledger_categories.bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account.more_accounts_below'

            )
                ->where('ledgers.item_code', 'like', $assetscode . '%')
                ->get();

            $account_group_liabilities = Ledger::with(
                'ledger_categories',
                'ledger_categories.bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account.more_accounts_below'

            )
                ->where('ledgers.item_code', 'like', $liabilitiescode . '%')
                ->get();

            $account_group_revenue = Ledger::with(
                'ledger_categories',
                'ledger_categories.bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account.more_accounts_below'

            )
                ->where('ledgers.item_code', 'like', $revenuecode . '%')
                ->get();

            $account_group_expenses = Ledger::with(
                'ledger_categories',
                'ledger_categories.bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account.more_accounts_below'

            )
                ->where('ledgers.item_code', 'like', $expensescode . '%')
                ->get();

            $account_group_equity = Ledger::with(
                'ledger_categories',
                'ledger_categories.bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account.more_accounts_below'

            )
                ->where('ledgers.item_code', 'like', $equitycode . '%')
                ->get();

            //return $account_group_assets;

            $data = array(
                'user' => Auth::user(),
                'account_group_assets' => $account_group_assets,
                'account_group_liabilities' => $account_group_liabilities,
                'account_group_expenses' => $account_group_expenses,
                'account_group_revenue' => $account_group_revenue,
                'account_group_equity' => $account_group_equity,
                'start_date' => $request->statement_start_date,
                'end_date' => $request->statement_end_date,
            );
            if ($request->report == "Trial Balance") {
            return view('chartofaccounts.trialbalancev')->with($data);
            }elseif($request->report == "Balance Sheet"){
                return view('chartofaccounts.balancesheet')->with($data);
            }else{
                return view('chartofaccounts.income')->with($data);
            }
    }

    public function generateAccountsReport()
    {

        $user = Auth::user();

        $data = array(
            'user' => $user,
        );

        return view('chartofaccounts.filterreports')->with($data);
    }

    public function accountshome(Request $request)
    {


            $assetscode = '3';
            $liabilitiescode = '4';
            $expensescode = '2';
            $revenuecode = '1';
            $equitycode = '5';
            $debit = 0;
            $credit = 0;

            $start_date = date("Y-m-01", strtotime(now()));
            $end_date = date("Y-m-t", strtotime(now()));

            //Get all assets from the GL

            $account_group_assets = Ledger::with(
                'ledger_categories',
                'ledger_categories.bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account.more_accounts_below'

            )
                ->where('ledgers.item_code', 'like', $assetscode . '%')
                ->get();

            $account_group_liabilities = Ledger::with(
                'ledger_categories',
                'ledger_categories.bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account.more_accounts_below'

            )
                ->where('ledgers.item_code', 'like', $liabilitiescode . '%')
                ->get();

            $account_group_revenue = Ledger::with(
                'ledger_categories',
                'ledger_categories.bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account.more_accounts_below'

            )
                ->where('ledgers.item_code', 'like', $revenuecode . '%')
                ->get();

            $account_group_expenses = Ledger::with(
                'ledger_categories',
                'ledger_categories.bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account.more_accounts_below'

            )
                ->where('ledgers.item_code', 'like', $expensescode . '%')
                ->get();

            $account_group_equity = Ledger::with(
                'ledger_categories',
                'ledger_categories.bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account.more_accounts_below'

            )
                ->where('ledgers.item_code', 'like', $equitycode . '%')
                ->get();

            //return $account_group_assets;

            $data = array(
                'user' => Auth::user(),
                'account_group_assets' => $account_group_assets,
                'account_group_liabilities' => $account_group_liabilities,
                'account_group_expenses' => $account_group_expenses,
                'account_group_revenue' => $account_group_revenue,
                'account_group_equity' => $account_group_equity,
                'start_date' => $start_date,
                'end_date' => $end_date,
            );

           return view('chartofaccounts.balancesheethome')->with($data);

    }

    //CLIENT STATEMENT
    public function ItemStatement()
    {
        //Get member accounts

        $all_accounts = array();
        $all_teller_accounts = array();
        $bank_accounts = DB::table('bank_accounts')->get();
        $sub_bank_accounts = DB::table('sub_bank_accounts')->get();
        $last_bank_accounts = DB::table('last_bank_accounts')->get();
        $more_account_belows = DB::table('more_account_belows')->get();


        foreach ($bank_accounts as $value) {
            $data1 = array(
                'name' => $value->name,
                'account_number' => $value->account_number,
            );
            array_push($all_accounts, $data1);
        }
        foreach ($sub_bank_accounts as $value) {
            $data2 = array(
                'name' => $value->name,
                'account_number' => $value->account_number,
            );
            array_push($all_accounts, $data2);
        }
        foreach ($last_bank_accounts as $value) {
            $data3 = array(
                'name' => $value->name,
                'account_number' => $value->account_number,
            );
            array_push($all_accounts, $data3);
        }

        foreach ($more_account_belows as $value) {
            $data4 = array(
                'name' => $value->name,
                'account_number' => $value->account_number,
            );
            array_push($all_accounts, $data4);
        }
        //return $all_accounts;
        $data = array(
            'all_accounts' => $all_accounts,
        );
        return view('chartofaccounts/itemstatement', [
            'all_accounts' => $all_accounts,
            'user' => Auth::user(),
        ]);
    }

    public function generateItemStatement(Request $request)
    {
       // return $request->account_number;
        $action = $request->action;

        if($action == 'excel'){
           $start_date = $request->statement_start_date;
           $end_date = $request->statement_end_date;
           $account = $request->account_number;
        //     //return $company_id;
            $fileNameToDownload = 'itemcode'.'_'.time().'.'.'xlsx';

             return Excel::download(new   ItemCodeExport($start_date, $end_date, $account), $fileNameToDownload);



         }else{

        //Get Item accounts

        $all_accounts = array();
        $bank_accounts = DB::table('bank_accounts')->get();
        $sub_bank_accounts = DB::table('sub_bank_accounts')->get();
        $last_bank_accounts = DB::table('last_bank_accounts')->get();
        $more_account_belows = DB::table('more_account_belows')->get();


        foreach ($bank_accounts as $value) {
            $data1 = array(
                'name' => $value->name,
                'account_number' => $value->account_number,
            );
            array_push($all_accounts, $data1);
        }
        foreach ($sub_bank_accounts as $value) {
            $data2 = array(
                'name' => $value->name,
                'account_number' => $value->account_number,
            );
            array_push($all_accounts, $data2);
        }
        foreach ($last_bank_accounts as $value) {
            $data3 = array(
                'name' => $value->name,
                'account_number' => $value->account_number,
            );
            array_push($all_accounts, $data3);
        }

        foreach ($more_account_belows as $value) {
            $data4 = array(
                'name' => $value->name,
                'account_number' => $value->account_number,
            );
            array_push($all_accounts, $data4);
        }

        $early_journal_entries = DB::table('general_ledgers')
            ->select('general_ledgers.*')
            ->whereDate('general_ledgers.created_at', '<', $request->statement_start_date)
            ->where('general_ledgers.account', $request->account_number)
            ->get();

        $account_journal_entries = GeneralLedger::whereBetween('general_ledgers.created_at', [
                $request->statement_start_date, $request->statement_end_date,
        ])->where('general_ledgers.account', $request->account_number)
        ->get();

        $all_account_journal_entries = GeneralLedger::whereBetween('general_ledgers.created_at', [
            $request->statement_start_date, $request->statement_end_date,
        ])->where('general_ledgers.account', $request->account_number)
        ->get();

        $debits1 = 0;
        $credits1 = 0;
        $credits = 0;
        $debits = 0;

        foreach ($early_journal_entries as $entries) {
            if ($entries->debit != null) {
                $debits = $debits + $entries->debit;
            }
        }

        foreach ($early_journal_entries as $entries) {
            if ($entries->credit != null) {
                $credits = $credits + $entries->credit;
            }
        }

        foreach ($all_account_journal_entries as $entries1) {
            if ($entries1->debit != null) {
                $debits1 = $debits1 + $entries1->debit;
            }
        }

        foreach ($all_account_journal_entries as $entries1) {
            if ($entries1->credit != null) {
                $credits1 = $credits1 + $entries1->credit;
            }
        }


        $Prev_amount_on_account =  $debits - $credits;

        //return $debits1.'-'.$credits1.'-'.$Prev_amount_on_account;

        if($Prev_amount_on_account > $debits1){

            $amount_on_account =    $Prev_amount_on_account + $debits1 - $credits1;

        }else{

        $amount_on_account =  $debits1 - $credits1;

        }

        return view('chartofaccounts/itemstatementview',  [
            'account_journal_entries' => $account_journal_entries,
            'Prev_amount_on_account' => $Prev_amount_on_account,
            'amount_on_account' => $amount_on_account,
            'start_date' => $request->statement_start_date,
            'end_date' => $request->statement_end_date,
            'all_accounts' => $all_accounts,
            'account' => $request->account_number,
            'user' => Auth::user(),
        ]);
      }
    }


    public function showEntries(Request $request) {



        //get request made
        $entries = DB::table('general_ledgers')
        ->select('general_ledgers.*')
        ->orderby('general_ledgers.created_at', 'DESC')
        ->get();

       // return $entries;




        $data = array(
            'user' => Auth::user(),
            'entries' => $entries,
        );

        return view('chartofaccounts.index')->with($data);

    }

}
