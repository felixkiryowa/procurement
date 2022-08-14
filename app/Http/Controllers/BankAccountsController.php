<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Models\Ledger;
use App\Models\LedgerCategory;
use App\Models\BankAccount;
use App\Traits\LogActivityTrait;
use Illuminate\Support\Facades\Validator;

class BankAccountsController extends Controller
{
    use LogActivityTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $ledgers = DB::table('ledgers')
        ->select('id', 'name', 'item_code', 'created_at')
        ->orderBy('ledgers.created_at', 'DESC')
        ->get();
        $ledger_categories = DB::table('ledger_categories')
        ->select('ledger_categories.id as ledger_category_id',
            'ledger_categories.name as category_name',
            'ledger_categories.ledger_id as account_group_id',
            'ledgers.name', 'ledger_categories.created_at', 'ledger_categories.item_code')
        ->leftJoin('ledgers', 'ledger_categories.ledger_id', '=', 'ledgers.id')
        ->orderBy('ledger_categories.created_at', 'DESC')
        ->get();

        $bank_accounts = DB::table('bank_accounts')
        ->select('bank_accounts.id as bank_id', 'bank_accounts.name as account_name',
            'bank_accounts.account_number',
            'ledger_categories.id as ledger_category_id',
            'ledgers.id as account_group_id', 'bank_accounts.account_number as item_code',
            'ledger_categories.name as category_name', 'ledgers.name as ledger_name', 'bank_accounts.created_at')
        ->leftJoin('ledgers', 'bank_accounts.ledger_id', '=', 'ledgers.id')
        ->leftJoin('ledger_categories', 'bank_accounts.ledger_category_id', '=', 'ledger_categories.id')
        ->orderBy('bank_accounts.created_at', 'DESC')
        ->get();

        return Inertia::render('ChartOfAccounts/BankAccountsComponent', [
            'ledgers' => $ledgers,
            'ledger_categories' => $ledger_categories,
            'bank_accounts' => $bank_accounts
        ]);
    }

    public function createBankAccount(Request $request)
    {
        $messages = [
            'account_group_id.required' => 'The Account Group field is required',
            'ledger_category_id.required' => 'The Ledger Category Name field is required',
            'account_name.required' => 'The Account Name field is required'
        ];

        $validator = Validator::make($request->all(), [
            'account_group_id' => 'required',
            'account_name' => 'required',
            'ledger_category_id' => 'required',
            'item_code' => 'required'
        ], $messages);
            
        //VALIDATE THE REQUEST INPUTS
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        // $last_bank_account_under_given_sub_account_groups = DB::table('bank_accounts')
        //     ->select('id', 'account_number', 'name',
        //     'ledger_category_id', 'ledger_id')
        //     ->where('bank_accounts.ledger_category_id',
        //      $request->ledger_category_id)
        //     ->latest('bank_accounts.created_at')
        //      ->first();

        // $sub_account_category = LedgerCategory::select('id', 'name',  'item_code')
        //      ->where('id', $request->ledger_category_id)->first();

        // if ($last_bank_account_under_given_sub_account_groups) {
        //     //Get last two digits of item code
        //     $new_string = substr($last_bank_account_under_given_sub_account_groups->account_number, -2);
        //     //convert string to integer
        //     $integer_string = (int) $new_string;

        //     if ($integer_string <= 9) {
        //         $new_last_digit = $integer_string + 1;
        //         $code = (string) $new_last_digit;
        //         $intact_code = '0' . $code;
        //         $new_item_code = $sub_account_category->item_code . '-' . $intact_code;
        //     } else {
        //         $new_last_digit = $integer_string + 1;
        //         $code = (string) $new_last_digit;
        //         $new_item_code = $sub_account_category->item_code . '-' . $code;
        //     }
            BankAccount::create([
                'ledger_category_id' => $request->ledger_category_id,
                'name' => str_replace("'", "''", trim(htmlspecialchars(ucwords($request->account_name)))),
                'account_number' => str_replace("'", "''", trim(htmlspecialchars(ucwords($request->item_code)))),
                'ledger_id' => $request->account_group_id,
            ]);

            //Log Ledger Creation
            $subject = 'Created a Bank Account';
            $details = 'Name: ' . $request->account_name;
            $this->addToLog($subject, $details);

            return response()->json([
                'success' => true,
                'message' => 'Sucessfully created a Bank Account',
            ], 201);
        // } else {
        //     $new_item_code = $sub_account_category->item_code . '-01';
        //     BankAccount::create([
        //         'ledger_category_id' => $request->ledger_category_id,
        //         'name' => str_replace("'", "''", trim(htmlspecialchars(ucwords($request->account_name)))),
        //         'account_number' => $new_item_code,
        //         'ledger_id' => $request->account_group_id,
        //     ]);

        //     $subject = 'Created a Bank Account';
        //     $details = 'Name: ' . $request->account_name;
        //     $this->addToLog($subject, $details);
        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Sucessfully created a Bank Account',
        //     ], 201);
        // }
    }

    public function updateBankAccount(Request $request)
    {
        $messages = [
            'account_group_id.required' => 'The Account Group field is required',
            'ledger_category_id.required' => 'The Ledger Category Name field is required',
            'account_name.required' => 'The Account Name field is required'
        ];

        $validator = Validator::make($request->all(), [
            'account_group_id' => 'required',
            'account_name' => 'required',
            'ledger_category_id' => 'required',
            'item_code' => 'required'
        ], $messages);
            
        //VALIDATE THE REQUEST INPUTS
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        //Retrieve the Bank and update
        $bank_account = BankAccount::select('id', 'ledger_category_id', 
        'ledger_id', 'name', 'account_number' )->where('id', $request->bank_id)->first();

        $bank_account->name = str_replace("'", "''", trim(htmlspecialchars(ucwords($request->account_name))));
        $bank_account->ledger_category_id = $request->ledger_category_id;
        $bank_account->ledger_id = $request->account_group_id;
        $bank_account->account_number = str_replace("'", "''", trim(htmlspecialchars(ucwords($request->item_code))));
        $bank_account->save();

        $subject = 'Updating a Bank Account';
        $details = 'Name: ' . $request->account_name;
        $this->addToLog($subject, $details);
        return response()->json([
            'success' => true,
            'message' => 'Sucessfully updated '.$request->account_name.' a Bank Account'
        ], 201);
       
    }
}
