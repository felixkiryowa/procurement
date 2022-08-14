<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Models\Ledger;
use App\Models\LedgerCategory;
use App\Models\BankAccount;
use App\Models\SubBankAccount;
use App\Models\LastBankAccount;
use App\Models\MoreAccountBelow;
use App\Models\Beneficiary;
use App\Models\GeneralLedger;
use App\Traits\LogActivityTrait;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Imports\ImportBeneficieriesExcelFile;
use Auth;
use Illuminate\Support\Facades\Crypt;


class BeneficieriesController extends Controller
{

    use LogActivityTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $all_accounts = array();
        $bank_accounts = DB::table('bank_accounts')->select('id', 'name', 'account_number')->get();
        $sub_bank_accounts = DB::table('sub_bank_accounts')->select('id', 'name', 'account_number')->get();
        $last_bank_accounts = DB::table('last_bank_accounts')->select('id', 'name', 'account_number')->get();
        $more_account_belows = DB::table('more_account_belows')->select('id', 'name', 'account_number')->get();

        // array_push($all_accounts, $bank_accounts);
        // array_push($all_accounts, $sub_bank_accounts);
        // array_push($all_accounts, $last_bank_accounts);
        // array_push($all_accounts, $more_account_belows);


        foreach ($bank_accounts as $value) {
            $data1 = array(
                // 'id' => $value->id,
                'name' => $value->name.' - '.$value->account_number,
                'account_number' => $value->account_number,
            );
            array_push($all_accounts, $data1);
        }
        foreach ($sub_bank_accounts as $value) {
            $data2 = array(
                // 'id' => $value->id,
                'name' => $value->name.' - '.$value->account_number,
                'account_number' => $value->account_number,
            );
            array_push($all_accounts, $data2);
        }
        foreach ($last_bank_accounts as $value) {
            $data3 = array(
                // 'id' => $value->id,
                'name' => $value->name.' - '.$value->account_number,
                'account_number' => $value->account_number,
            );
            array_push($all_accounts, $data3);
        }

        foreach ($more_account_belows as $value) {
            $data4 = array(
                // 'id' => $value->id,
                'name' => $value->name.' - '.$value->account_number,
                'account_number' => $value->account_number,
            );
            array_push($all_accounts, $data4);
        }

        return Inertia::render('ChartOfAccounts/UploadBeneficiariesComponent', [
            'all_accounts' => $all_accounts,
        ]);
       // return Inertia::render('ChartOfAccounts/UploadBeneficiariesComponent');

        // return view('upload');
    }

    public function addBenficiaryLedgerEntries($amount, $beneficiary_name, $debit_account, $credit_account, $requested_by, $category, $details) {


        $get_last_reference_number = DB::table('general_ledgers')->select('general_ledgers.*')
            ->latest('general_ledgers.reference')->first();

        if ($get_last_reference_number) {
            $defaut_journal_reference_code = $get_last_reference_number->reference;
            $finalcode = str_pad(intval($defaut_journal_reference_code) + 1, strlen($defaut_journal_reference_code), '0', STR_PAD_LEFT);
        } else {
            $initialreferencecode = '0000001';
            $finalcode = $initialreferencecode;
        }

        //DEBIT ACCOUNT
        $general_ledger1 = GeneralLedger::create([
            'details' => $details,
            'account' => $debit_account,
            'debit' => $amount,
            'transaction_date' => date('Y-m-d h:i:s'),
            'reference' => $finalcode,
            'requested_by' => $requested_by,
            'category' => $category,

        ]);

        //CREDIT ACCOUNT

        if ($general_ledger1) {
            GeneralLedger::create([
                'details' => $details,
                'account' => $credit_account,
                'credit' => $amount,
                'transaction_date' => date('Y-m-d h:i:s'),
                'reference' => $finalcode,
                'requested_by' => $requested_by,
                'category' => $category,
            ]);
        }
    }

    public function handleUploadingOfBeneficiaries(Request $request) {

        //return $request->item_code;

        $rows = Excel::toArray(new ImportBeneficieriesExcelFile, $request->uploaded_attachment);

        for ($i = 0; $i < count($rows) ; $i++) {

            $row = $rows[0];
        }

        for ($i=0; $i < count($row) ; $i++) {

            if($i > 1) {

                // CHECK IF BENEFICIARY  HAS BEEN REGISTERED BEFORE
                $beneficiary = Beneficiary::select('id',
                'account_number')->where('account_number', $row[$i][5] )->first();

                if($beneficiary == NULL) {
                    // CREATE NEW BENEFICIARY
                    $user = Beneficiary::create([
                        'district' => $row[$i][0],
                        'name' => $row[$i][1],
                        'title' => $row[$i][2],
                        'duty_station' => $row[$i][3],
                        'bank' => $row[$i][4],
                        'account_number' => $row[$i][5],
                        'telephone_number' => $row[$i][6]
                    ]);

                    // //Get the last created sub account under a sub account
                    $last_account = DB::table('more_account_belows')
                        ->select('more_account_belows.id', 'more_account_belows.created_at',
                        'more_account_belows.account_below_bank_account_id', 'more_account_belows.account_number')
                        ->latest('more_account_belows.id')
                        ->where('more_account_belows.account_below_bank_account_id', 18)
                        ->first();

                    //Get information about a given sub account
                    $sub_account = LastBankAccount::select('id', 'account_number')->where('id', 18)->first();
                    $amount = (int)  $row[$i][7];


                    if ($last_account != NULL) {
                        //Get last two digits of item code
                        $new_string = substr($last_account->account_number, -2);
                        //convert string to integer
                        $integer_string = (int) $new_string;

                        if ($integer_string <= 9) {
                            $new_last_digit = $integer_string + 1;
                            $code = (string) $new_last_digit;
                            $intact_code = '0' . $code;
                            $new_item_code = $sub_account->account_number . $intact_code;
                        } else {
                            $new_last_digit = $integer_string + 1;
                            $code = (string) $new_last_digit;
                            $new_item_code = $sub_account->account_number  . $code;
                        }

                        $created_account = MoreAccountBelow::create([
                            'account_below_bank_account_id' => 18,
                            'name' => ucwords($row[$i][1]),
                            'account_number' => $new_item_code
                        ]);
                        $subject = 'Creating a beneficiary account';
                        $details = 'Creating account with name : ' . $row[$i][1];
                        $this->addToLog($subject, $details);

                        if($created_account) {
                            $select_beneficiary = Beneficiary::select('id',
                            'account_number', 'code_in_coa')->where('account_number', $row[$i][5] )->first();
                            $select_beneficiary->code_in_coa = $created_account->account_number;
                            $select_beneficiary->save();
                        }
                        $this->addBenficiaryLedgerEntries($amount, $row[$i][1],
                        $new_item_code, $request->item_code,$row[$i][11],$row[$i][10],$row[$i][12]);

                    } else {
                        $new_item_code = $sub_account->account_number . '01';
                        $created_account = MoreAccountBelow::create([
                            'account_below_bank_account_id' => 18,
                            'name' => ucwords($row[$i][1]),
                            'account_number' => $new_item_code
                        ]);
                        $subject = 'Creating a beneficiary account';
                        $details = 'Creating account with name : ' . $row[$i][1];
                        $this->addToLog($subject, $details);

                        if($created_account) {
                            $select_beneficiary = Beneficiary::select('id',
                            'account_number', 'code_in_coa')->where('account_number', $row[$i][5] )->first();
                            $select_beneficiary->code_in_coa = $created_account->account_number;
                            $select_beneficiary->save();
                        }
                        $this->addBenficiaryLedgerEntries($amount, $row[$i][1],
                        $new_item_code, $request->item_code,$row[$i][11],$row[$i][10],$row[$i][12]);
                    }

                }else {

                    // IF BENFICIARY HAS ALREADY BEEN REGISTERED
                    $amount = (int)  $row[$i][7];
                    //GET BENEFICIARY ACCOUNT CODE  FROM CHART OF ACCOUNTS
                    $select_beneficiary = Beneficiary::select('id',
                    'account_number', 'name', 'code_in_coa')->where('account_number', $row[$i][5] )->first();

                    $this->addBenficiaryLedgerEntries($amount, $select_beneficiary->name,
                     $select_beneficiary->code_in_coa, $request->item_code,$row[$i][11],$row[$i][10],$row[$i][12]);

                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Sucessfully Add Ledger Entries',
        ], 200);
    }

    public function showBeneficiairies(Request $request) {

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


    //get request made
    $entries = DB::table('beneficiaries')
    ->select('beneficiaries.*')
    ->orderby('beneficiaries.created_at', 'DESC')
    ->get();

   // return $entries;




    $data = array(
        'user' => Auth::user(),
        'entries' => $entries,
        'all_accounts' => $all_accounts,
    );

    return view('beneficiaries.index')->with($data);

    }

    public function createBeneficiairy(Request $request) {

        //return $request->item_code;

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'district' => 'required',
            'account_number' => 'required',
            'telephone_number' => 'required',
            'amount' => 'required',
            'item_code' => 'required',

        ]);



        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        //Check if Account Exists
        $beneficiary = Beneficiary::select('id',
                'account_number')->where('account_number', $request->account_number)->first();
        if($beneficiary == NULL) {
            // CREATE NEW BENEFICIARY
            $user = Beneficiary::create([
                'district' => $request->district,
                'name' => $request->name,
                'title' => $request->title,
                'duty_station' => $request->duty_station,
                'bank' => $request->bank,
                'account_number' => $request->account_number,
                'telephone_number' => $request->telephone_number,
            ]);

            // //Get the last created sub account under a sub account
            $last_account = DB::table('more_account_belows')
                ->select('more_account_belows.id', 'more_account_belows.created_at',
                'more_account_belows.account_below_bank_account_id', 'more_account_belows.account_number')
                ->latest('more_account_belows.id')
                ->where('more_account_belows.account_below_bank_account_id', 18)
                ->first();

            //Get information about a given sub account
            $sub_account = LastBankAccount::select('id', 'account_number')->where('id', 18)->first();
            $amount = (int)  $request->amount;


            if ($last_account != NULL) {
                //Get last two digits of item code
                $new_string = substr($last_account->account_number, -2);
                //convert string to integer
                $integer_string = (int) $new_string;

                if ($integer_string <= 9) {
                    $new_last_digit = $integer_string + 1;
                    $code = (string) $new_last_digit;
                    $intact_code = '0' . $code;
                    $new_item_code = $sub_account->account_number . $intact_code;
                } else {
                    $new_last_digit = $integer_string + 1;
                    $code = (string) $new_last_digit;
                    $new_item_code = $sub_account->account_number  . $code;
                }

                $created_account = MoreAccountBelow::create([
                    'account_below_bank_account_id' => 18,
                    'name' => ucwords($request->name),
                    'account_number' => $new_item_code
                ]);
                $subject = 'Creating a beneficiary account';
                $details = 'Creating account with name : ' . $request->name;
                $this->addToLog($subject, $details);

                if($created_account) {
                    $select_beneficiary = Beneficiary::select('id',
                    'account_number', 'code_in_coa')->where('account_number', $request->account_number )->first();
                    $select_beneficiary->code_in_coa = $created_account->account_number;
                    $select_beneficiary->save();
                }
                $this->addBenficiaryLedgerEntries($amount, $request->name,
                $new_item_code, $request->item_code);

            } else {
                $new_item_code = $sub_account->account_number . '01';
                $created_account = MoreAccountBelow::create([
                    'account_below_bank_account_id' => 18,
                    'name' => ucwords($request->name),
                    'account_number' => $new_item_code
                ]);
                $subject = 'Creating a beneficiary account';
                $details = 'Creating account with name : ' . $request->name;
                $this->addToLog($subject, $details);

                if($created_account) {
                    $select_beneficiary = Beneficiary::select('id',
                    'account_number', 'code_in_coa')->where('account_number', $request->account_number )->first();
                    $select_beneficiary->code_in_coa = $created_account->account_number;
                    $select_beneficiary->save();
                }
                $this->addBenficiaryLedgerEntries($amount, $request->name,
                $new_item_code, $request->item_code , $request->requested_by, $request->category, $request->details);
            }

        }else {

            // IF BENFICIARY HAS ALREADY BEEN REGISTERED
            $amount = (int)  $request->amount;
            //GET BENEFICIARY ACCOUNT CODE  FROM CHART OF ACCOUNTS
            $select_beneficiary = Beneficiary::select('id',
            'account_number', 'name', 'code_in_coa')->where('account_number', $request->account_number )->first();

            $this->addBenficiaryLedgerEntries($amount, $select_beneficiary->name,
             $select_beneficiary->code_in_coa, $request->item_code, $request->requested_by, $request->category, $request->details);

        }


        return redirect('list/beneficiaries')->with('message', 'Successfully Added Beneficiary');
    }

    public function entryedit($id)
    {

        $id = Crypt::decrypt($id);

        $item = Beneficiary::find($id);

        $data = array(
            'user' => Auth::user(),
            'item' => $item,
        );
        return view('beneficiaries.edit')->with($data);


    }

    public function entryupdate(Request $request)
    {


    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'bank' => 'required',
        'account_number' => 'unique:beneficiaries,account_number,' . $request->id,

    ]);



    if ($validator->fails()) {
        return redirect()->back()->withInput()->withErrors($validator);
    }

   // return 'hi';


    $item = Beneficiary::find( $request->input( 'id' ) );






        $item->name = ucfirst($request->name);
        $item->bank = $request->bank;
        $item->account_number = $request->account_number;
        $item->title = $request->title;
        $item->duty_station = $request->duty_station;
        $item->telephone_number = $request->telephone_number;
        $item->district = $request->district;


        $item->save();


    return redirect('/list/beneficiaries')->with('success', 'Successfully Updated Beneficiary ');


}



}
