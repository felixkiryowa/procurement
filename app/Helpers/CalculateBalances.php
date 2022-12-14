<?php

namespace App\Helpers;

use App\Models\BankAccount;
use App\Models\LastBankAccount;
use App\Models\SubBankAccount;
use Auth;
use DB;

trait CalculateBalances
{

    public static function calculate_amount_on_accounts($account_number, $account_id, $account_level)
    {
        $user_id = Auth::user()->id;
        $employee = DB::table('employees')->select('employees.branch_id', 'employees.user_id')
            ->where('employees.user_id', $user_id)->first();
        if ($account_level == 1) {
            $account_group = BankAccount::with(
                'sub_bank_accounts',
                'sub_bank_accounts.accounts_below_sub_account',
                'sub_bank_accounts.accounts_below_sub_account.more_accounts_below'
            )
                ->where('id', $account_id)
                ->get();

            $result = CalculateBalances::calculate_total_amounts_on_top_accounts($account_group, $account_number);

            return $result;

        } else if ($account_level == 2) {
            $account_group = SubBankAccount::with(
                'accounts_below_sub_account',
                'accounts_below_sub_account.more_accounts_below'

            )
                ->where('id', $account_id)
                ->get();

            $result = CalculateBalances::calculate_total_amounts_on_sub_bank_accounts($account_group, $account_number);

            return $result;
        } else if ($account_level == 3) {
            $account_group = LastBankAccount::with(
                'more_accounts_below'
            )
                ->where('id', $account_id)
                ->get();

            $result = CalculateBalances::calculate_total_amounts_on_last_bank_accounts($account_group, $account_number);

            return $result;
        }
    }

    //Run Logic on last bank accounts
    public static function get_amounts_on_last_accounts_below_sub_accounts($account_group, $category)
    {
        $credits = 0;
        $deposits = 0;
        $user_id = Auth::user()->id;
        $employee = DB::table('employees')->select('employees.branch_id', 'employees.user_id')
            ->where('employees.user_id', $user_id)->first();

        if ($employee != null) {
            foreach ($account_group as $value3) {
                $journal_entries3 = DB::table('general_ledgers')
                    ->select('general_ledgers.*')
                    ->orderBy('general_ledgers.created_at', 'DESC')
                    ->where('general_ledgers.branch_id', $employee->branch_id)
                    ->where('general_ledgers.account', $value3->account_number)
                    ->get();

                foreach ($journal_entries3 as $entries3) {
                    if ($entries3->debit != null) {
                        $deposits = $deposits + $entries3->debit;
                    }
                    if ($entries3->credit != null) {
                        $credits = $credits + $entries3->credit;
                    }
                }

                foreach ($value3->more_accounts_below as $value4) {
                    $journal_entries4 = DB::table('general_ledgers')
                        ->select('general_ledgers.*')
                        ->orderBy('general_ledgers.created_at', 'DESC')
                        ->where('general_ledgers.branch_id', $employee->branch_id)
                        ->where('general_ledgers.account', $value4->account_number)
                        ->get();

                    foreach ($journal_entries4 as $entries4) {
                        if ($entries4->debit != null) {
                            $deposits = $deposits + $entries4->debit;
                        }
                        if ($entries4->credit != null) {
                            $credits = $credits + $entries4->credit;
                        }
                    }
                }
            }
        } else {
            foreach ($account_group as $value3) {
                $journal_entries3 = DB::table('general_ledgers')
                    ->select('general_ledgers.*')
                    ->orderBy('general_ledgers.created_at', 'DESC')
                    ->where('general_ledgers.account', $value3->account_number)
                    ->get();

                foreach ($journal_entries3 as $entries3) {
                    if ($entries3->debit != null) {
                        $deposits = $deposits + $entries3->debit;
                    }
                    if ($entries3->credit != null) {
                        $credits = $credits + $entries3->credit;
                    }
                }

                foreach ($value3->more_accounts_below as $value4) {
                    $journal_entries4 = DB::table('general_ledgers')
                        ->select('general_ledgers.*')
                        ->orderBy('general_ledgers.created_at', 'DESC')
                        ->where('general_ledgers.account', $value4->account_number)
                        ->get();

                    foreach ($journal_entries4 as $entries4) {
                        if ($entries4->debit != null) {
                            $deposits = $deposits + $entries4->debit;
                        }
                        if ($entries4->credit != null) {
                            $credits = $credits + $entries4->credit;
                        }
                    }
                }
            }
        }

        if ($category == 'assets') {
            return ($deposits - $credits);
        } else if ($category == 'liabilities') {
            return ($credits - $deposits);
        } else if ($category == 'expenses') {
            return ($deposits - $credits);
        } else if ($category == 'revenue') {
            return ($credits - $deposits);
        } else if ($category == 'equity') {
            return ($credits - $deposits);
        }
    }

    //Run Logic on top back accounts
    public static function get_amounts_on_top_accounts($account_group, $category)
    {
        $credits = 0;
        $deposits = 0;
        $user_id = Auth::user()->id;

        $employee = DB::table('employees')->select('employees.branch_id', 'employees.user_id')
            ->where('employees.user_id', $user_id)->first();

        if ($employee != null) {
            foreach ($account_group as $value) {
                $journal_entries1 = DB::table('general_ledgers')
                    ->select('general_ledgers.*')
                    ->orderBy('general_ledgers.created_at', 'DESC')
                    ->where('general_ledgers.branch_id', $employee->branch_id)
                    ->where('general_ledgers.account', $value->account_number)
                    ->get();

                foreach ($journal_entries1 as $entries1) {
                    if ($entries1->debit != null) {
                        $deposits = $deposits + $entries1->debit;
                    }
                    if ($entries1->credit != null) {
                        $credits = $credits + $entries1->credit;
                    }
                }
                foreach ($value->sub_bank_accounts as $value2) {
                    $journal_entries2 = DB::table('general_ledgers')
                        ->select('general_ledgers.*')
                        ->orderBy('general_ledgers.created_at', 'DESC')
                        ->where('general_ledgers.branch_id', $employee->branch_id)
                        ->where('general_ledgers.account', $value2->account_number)
                        ->get();

                    foreach ($journal_entries2 as $entries2) {
                        if ($entries2->debit != null) {
                            $deposits = $deposits + $entries2->debit;
                        }
                        if ($entries2->credit != null) {
                            $credits = $credits + $entries2->credit;
                        }
                    }

                    foreach ($value2->accounts_below_sub_account as $value3) {
                        $journal_entries3 = DB::table('general_ledgers')
                            ->select('general_ledgers.*')
                            ->orderBy('general_ledgers.created_at', 'DESC')
                            ->where('general_ledgers.branch_id', $employee->branch_id)
                            ->where('general_ledgers.account', $value3->account_number)
                            ->get();

                        foreach ($journal_entries3 as $entries3) {
                            if ($entries3->debit != null) {
                                $deposits = $deposits + $entries3->debit;
                            }
                            if ($entries3->credit != null) {
                                $credits = $credits + $entries3->credit;
                            }
                        }

                        foreach ($value3->more_accounts_below as $value4) {
                            $journal_entries4 = DB::table('general_ledgers')
                                ->select('general_ledgers.*')
                                ->orderBy('general_ledgers.created_at', 'DESC')
                                ->where('general_ledgers.branch_id', $employee->branch_id)
                                ->where('general_ledgers.account', $value4->account_number)
                                ->get();

                            foreach ($journal_entries4 as $entries4) {
                                if ($entries4->debit != null) {
                                    $deposits = $deposits + $entries4->debit;
                                }
                                if ($entries4->credit != null) {
                                    $credits = $credits + $entries4->credit;
                                }
                            }
                        }
                    }
                }
            }
        } else {
            foreach ($account_group as $value) {
                $journal_entries1 = DB::table('general_ledgers')
                    ->select('general_ledgers.*')
                    ->orderBy('general_ledgers.created_at', 'DESC')
                    ->where('general_ledgers.account', $value->account_number)
                    ->get();

                foreach ($journal_entries1 as $entries1) {
                    if ($entries1->debit != null) {
                        $deposits = $deposits + $entries1->debit;
                    }
                    if ($entries1->credit != null) {
                        $credits = $credits + $entries1->credit;
                    }
                }
                foreach ($value->sub_bank_accounts as $value2) {
                    $journal_entries2 = DB::table('general_ledgers')
                        ->select('general_ledgers.*')
                        ->orderBy('general_ledgers.created_at', 'DESC')
                        ->where('general_ledgers.account', $value2->account_number)
                        ->get();

                    foreach ($journal_entries2 as $entries2) {
                        if ($entries2->debit != null) {
                            $deposits = $deposits + $entries2->debit;
                        }
                        if ($entries2->credit != null) {
                            $credits = $credits + $entries2->credit;
                        }
                    }

                    foreach ($value2->accounts_below_sub_account as $value3) {
                        $journal_entries3 = DB::table('general_ledgers')
                            ->select('general_ledgers.*')
                            ->orderBy('general_ledgers.created_at', 'DESC')
                            ->where('general_ledgers.account', $value3->account_number)
                            ->get();

                        foreach ($journal_entries3 as $entries3) {
                            if ($entries3->debit != null) {
                                $deposits = $deposits + $entries3->debit;
                            }
                            if ($entries3->credit != null) {
                                $credits = $credits + $entries3->credit;
                            }
                        }

                        foreach ($value3->more_accounts_below as $value4) {
                            $journal_entries4 = DB::table('general_ledgers')
                                ->select('general_ledgers.*')
                                ->orderBy('general_ledgers.created_at', 'DESC')
                                ->where('general_ledgers.account', $value4->account_number)
                                ->get();

                            foreach ($journal_entries4 as $entries4) {
                                if ($entries4->debit != null) {
                                    $deposits = $deposits + $entries4->debit;
                                }
                                if ($entries4->credit != null) {
                                    $credits = $credits + $entries4->credit;
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($category == 'assets') {
            return ($deposits - $credits);
        } else if ($category == 'liabilities') {
            return ($credits - $deposits);
        } else if ($category == 'expenses') {
            return ($deposits - $credits);
        } else if ($category == 'revenue') {
            return ($credits - $deposits);
        } else if ($category == 'equity') {
            return ($credits - $deposits);
        }

    }

    //Run logic on sub bank accounts
    public static function get_amounts_on_sub_accounts($account_group, $category)
    {
        $credits = 0;
        $deposits = 0;
        $user_id = Auth::user()->id;
        $employee = DB::table('employees')->select('employees.branch_id', 'employees.user_id')
            ->where('employees.user_id', $user_id)->first();

        if ($employee != null) {
            foreach ($account_group as $value) {
                $journal_entries1 = DB::table('general_ledgers')
                    ->select('general_ledgers.*')
                    ->orderBy('general_ledgers.created_at', 'DESC')
                    ->where('general_ledgers.branch_id', $employee->branch_id)
                    ->where('general_ledgers.account', $value->account_number)
                    ->get();

                foreach ($journal_entries1 as $entries1) {
                    if ($entries1->debit != null) {
                        $deposits = $deposits + $entries1->debit;
                    }
                    if ($entries1->credit != null) {
                        $credits = $credits + $entries1->credit;
                    }
                }

                foreach ($value->accounts_below_sub_account as $value1) {
                    $journal_entries1 = DB::table('general_ledgers')
                        ->select('general_ledgers.*')
                        ->orderBy('general_ledgers.created_at', 'DESC')
                        ->where('general_ledgers.branch_id', $employee->branch_id)
                        ->where('general_ledgers.account', $value1->account_number)
                        ->get();

                    foreach ($journal_entries1 as $entries1) {
                        if ($entries1->debit != null) {
                            $deposits = $deposits + $entries1->debit;
                        }
                        if ($entries1->credit != null) {
                            $credits = $credits + $entries1->credit;
                        }
                    }

                    foreach ($value1->more_accounts_below as $value2) {
                        $journal_entries2 = DB::table('general_ledgers')
                            ->select('general_ledgers.*')
                            ->orderBy('general_ledgers.created_at', 'DESC')
                            ->where('general_ledgers.branch_id', $employee->branch_id)
                            ->where('general_ledgers.account', $value2->account_number)
                            ->get();

                        foreach ($journal_entries2 as $entries2) {
                            if ($entries2->debit != null) {
                                $deposits = $deposits + $entries2->debit;
                            }
                            if ($entries2->credit != null) {
                                $credits = $credits + $entries2->credit;
                            }
                        }
                    }
                }
            }
        } else {
            foreach ($account_group as $value) {
                $journal_entries1 = DB::table('general_ledgers')
                    ->select('general_ledgers.*')
                    ->orderBy('general_ledgers.created_at', 'DESC')
                    ->where('general_ledgers.account', $value->account_number)
                    ->get();

                foreach ($journal_entries1 as $entries1) {
                    if ($entries1->debit != null) {
                        $deposits = $deposits + $entries1->debit;
                    }
                    if ($entries1->credit != null) {
                        $credits = $credits + $entries1->credit;
                    }
                }

                foreach ($value->accounts_below_sub_account as $value1) {
                    $journal_entries1 = DB::table('general_ledgers')
                        ->select('general_ledgers.*')
                        ->orderBy('general_ledgers.created_at', 'DESC')
                        ->where('general_ledgers.account', $value1->account_number)
                        ->get();

                    foreach ($journal_entries1 as $entries1) {
                        if ($entries1->debit != null) {
                            $deposits = $deposits + $entries1->debit;
                        }
                        if ($entries1->credit != null) {
                            $credits = $credits + $entries1->credit;
                        }
                    }

                    foreach ($value1->more_accounts_below as $value2) {
                        $journal_entries2 = DB::table('general_ledgers')
                            ->select('general_ledgers.*')
                            ->orderBy('general_ledgers.created_at', 'DESC')
                            ->where('general_ledgers.account', $value2->account_number)
                            ->get();

                        foreach ($journal_entries2 as $entries2) {
                            if ($entries2->debit != null) {
                                $deposits = $deposits + $entries2->debit;
                            }
                            if ($entries2->credit != null) {
                                $credits = $credits + $entries2->credit;
                            }
                        }
                    }
                }
            }
        }

        if ($category == 'assets') {
            return ($deposits - $credits);
        } else if ($category == 'liabilities') {
            return ($credits - $deposits);
        } else if ($category == 'expenses') {
            return ($deposits - $credits);
        } else if ($category == 'revenue') {
            return ($credits - $deposits);
        } else if ($category == 'equity') {
            return ($credits - $deposits);
        }
    }

    public static function calculate_total_amounts_on_top_accounts($account_group, $account_number)
    {
        //LOGIC  TO DO  CHECK WHETHER IT IS A DEBIT OR CREDIT
        $specific_account_number = $account_number;
        //SEPARATING THE ACCOUNT TO GET PARTS OF AN ACCOUNT
        $x = explode('-', $specific_account_number);
        $chopped_account_number = $x[0] . '-' . $x[1];
        $deposits = 0;
        $credits = 0;

        $first_letter = $account_number[0];

        if ($first_letter == '3') {
            // ASSETS ACCOUNT
            $result = CalculateBalances::get_amounts_on_top_accounts($account_group, 'assets');
            //ASSETS ACCOUNT
        } else if ($first_letter == '4') {
            //LIABILITIES
            $result = CalculateBalances::get_amounts_on_top_accounts($account_group, 'liabilities');
            //LIABILITIES

        } else if ($first_letter == '2') {
            //EXPENSES
            $result = CalculateBalances::get_amounts_on_top_accounts($account_group, 'expenses');
            //EXPENSES
        } else if ($first_letter == '1') {
            //REVENUE
            $result = CalculateBalances::get_amounts_on_top_accounts($account_group, 'revenue');
            //REVENUE
        } else {
            // EQUITY ACCOUNT
            $result = CalculateBalances::get_amounts_on_top_accounts($account_group, 'equity');
            // EQUITY ACCOUNT
        }

        return $result;
    }

    //Function to calculate amounts on sub bank accounts
    public static function calculate_total_amounts_on_sub_bank_accounts($account_group, $account_number)
    {
        //LOGIC  TO DO  CHECK WHETHER IT IS A DEBIT OR CREDIT
        $check_if_its_generated_account_company_aggregated_account = DB::table('sub_bank_accounts')->select('sub_bank_accounts.*')
            ->where('sub_bank_accounts.account_number', $account_number)
            ->where('sub_bank_accounts.bank_account_id', 151)
            ->first();
        $check_if_its_generated_account_is_client_savings_account = DB::table('sub_bank_accounts')->select('sub_bank_accounts.*')
            ->where('sub_bank_accounts.account_number', $account_number)
            ->where('sub_bank_accounts.bank_account_id', 83)
            ->first();
        $check_if_its_generated_account_is_beneficiary_savings_account = DB::table('sub_bank_accounts')->select('sub_bank_accounts.*')
            ->where('sub_bank_accounts.account_number', $account_number)
            ->where('sub_bank_accounts.bank_account_id', 98)
            ->first();
        $check_if_its_generated_account_is_client_compulsory_account = DB::table('sub_bank_accounts')->select('sub_bank_accounts.*')
            ->where('sub_bank_accounts.account_number', $account_number)
            ->where('sub_bank_accounts.bank_account_id', 75)
            ->first();

        if ($check_if_its_generated_account_company_aggregated_account) {
            $result = CalculateBalances::specialSubAccountsAmounts($account_number);
            return $result;
        } else if ($check_if_its_generated_account_is_client_savings_account) {
            $result = CalculateBalances::specialSubAccountsAmounts($account_number);
            return $result;
        } else if ($check_if_its_generated_account_is_beneficiary_savings_account) {
            $result = CalculateBalances::specialSubAccountsAmounts($account_number);
            return $result;
        } else if ($check_if_its_generated_account_is_client_compulsory_account) {
            $result = CalculateBalances::specialSubAccountsAmounts($account_number);
            return $result;
        } else {
            //LOGIC  TO DO  CHECK WHETHER IT IS A DEBIT OR CREDIT
            $specific_account_number = $account_number;
            //SEPARATING THE ACCOUNT TO GET PARTS OF AN ACCOUNT
            $x = explode('-', $specific_account_number);
            $chopped_account_number = $x[0] . '-' . $x[1];
            $deposits = 0;
            $credits = 0;

            $first_letter = $account_number[0];

            if ($first_letter == '3') {
                // ASSETS ACCOUNT
                $result = CalculateBalances::get_amounts_on_sub_accounts($account_group, 'assets');
                // ASSETS ACCOUNT
            } else if ($first_letter == '4') {
                // LIABILITIES
                $result = CalculateBalances::get_amounts_on_sub_accounts($account_group, 'liabilities');
                // LIABILITIES
            } else if ($first_letter == '2') {
                // EXPENSES
                $result = CalculateBalances::get_amounts_on_sub_accounts($account_group, 'expenses');
                // EXPENSES
            } else if ($first_letter == '1') {
                // REVENUE
                $result = CalculateBalances::get_amounts_on_sub_accounts($account_group, 'revenue');
                // REVENUE
            } else {
                // EQUITY ACCOUNT
                $result = CalculateBalances::get_amounts_on_sub_accounts($account_group, 'equity');
                // EQUITY ACCOUNT
            }

            return $result;
        }
    }

    //Function to calculate amounts on last bank accounts
    public static function calculate_total_amounts_on_last_bank_accounts($account_group, $account_number)
    {
        //LOGIC  TO DO  CHECK WHETHER IT IS A DEBIT OR CREDIT
        $specific_account_number = $account_number;
        //SEPARATING THE ACCOUNT TO GET PARTS OF AN ACCOUNT
        $x = explode('-', $specific_account_number);
        $chopped_account_number = $x[0] . '-' . $x[1];
        $deposits = 0;
        $credits = 0;
        $first_letter = $account_number[0];

        if ($first_letter == '3') {
            // ASSETS ACCOUNT
            $result = CalculateBalances::get_amounts_on_last_accounts_below_sub_accounts($account_group, 'assets');
            //ASSETS ACCOUNT
        } else if ($first_letter == '4') {
            //LIABILITIES
            $result = CalculateBalances::get_amounts_on_last_accounts_below_sub_accounts($account_group, 'liabilities');
            //LIABILITIES

        } else if ($first_letter == '2') {
            //EXPENSES
            $result = CalculateBalances::get_amounts_on_last_accounts_below_sub_accounts($account_group, 'expenses');
            //EXPENSES
        } else if ($first_letter == '1') {
            //REVENUE
            $result = CalculateBalances::get_amounts_on_last_accounts_below_sub_accounts($account_group, 'revenue');
            //REVENUE
        } else {
            // EQUITY ACCOUNT
            $result = CalculateBalances::get_amounts_on_last_accounts_below_sub_accounts($account_group, 'equity');
            // EQUITY ACCOUNT
        }

        return $result;
    }

    //Function to get money on last bank accounts
    public static function lastBankAccountAmountsOnAccounts($account_number)
    {
        $deposits2 = 0;
        $credits2 = 0;
        $journal_entries = DB::table('general_ledgers')
            ->select('general_ledgers.*')
            ->orderBy('general_ledgers.created_at', 'DESC')
            ->where('general_ledgers.account', $account_number)
            ->get();

        foreach ($journal_entries as $entries) {
            if ($entries->debit != null) {
                $deposits2 = $deposits2 + $entries->debit;
            }
            if ($entries->credit != null) {
                $credits2 = $credits2 + $entries->credit;
            }
        }

        return ($deposits2 - $credits2);
    }

    //Function to run get amounts on company aggregated accounts, compulsory and savings
    public static function specialSubAccountsAmounts($account_number)
    {
        $deposits2 = 0;
        $credits2 = 0;
        $journal_entries = DB::table('general_ledgers')
            ->select('general_ledgers.*')
            ->orderBy('general_ledgers.created_at', 'DESC')
            ->where('general_ledgers.account', $account_number)
            ->get();

        foreach ($journal_entries as $entries) {
            if ($entries->debit != null) {
                $deposits2 = $deposits2 + $entries->debit;
            }
            if ($entries->credit != null) {
                $credits2 = $credits2 + $entries->credit;
            }
        }

        $result = $credits2 - $deposits2;
        return $result;
    }

    public static function clientBalanceCredits($account_number, $amount)
    {
        $total_on_account = 0;
        $deposits = 0;
        $credits = 0;
        $journal_entries = DB::table('general_ledgers')
            ->select('general_ledgers.*')
            ->orderBy('general_ledgers.created_at', 'DESC')
            ->where('general_ledgers.account', $account_number)
            ->get();

        foreach ($journal_entries as $entries) {
            if ($entries->debit != null) {
                $deposits = $deposits + $entries->debit;
            }
            if ($entries->credit != null) {
                $credits = $credits + $entries->credit;
            }
        }

        $amount_on_account = $credits + $amount;
        $total_on_account = $amount_on_account;
        return $total_on_account;
    }

    public static function clientBalanceDebits($account_number, $amount)
    {
        $total_on_account = 0;
        $deposits = 0;
        $credits = 0;
        $journal_entries = DB::table('general_ledgers')
            ->select('general_ledgers.*')
            ->orderBy('general_ledgers.created_at', 'DESC')
            ->where('general_ledgers.account', $account_number)
            ->get();

        foreach ($journal_entries as $entries) {
            if ($entries->debit != null) {
                $deposits = $deposits + $entries->debit;
            }
            if ($entries->credit != null) {
                $credits = $credits + $entries->credit;
            }
        }

        if ($credits == 0) {
            $amount_on_account = $amount;
        } else {
            $amount_on_account = $credits - $amount;
        }

        $total_on_account = $amount_on_account;
        return $total_on_account;
    }

    //Function to calculate amounts on sub accounts
    public static function amounts_on_sub_accounts($account_number)
    {
        $deposits = 0;
        $credits = 0;
        $journal_entries = DB::table('general_ledgers')
            ->select('general_ledgers.*')
            ->orderBy('general_ledgers.created_at', 'DESC')
            ->where('general_ledgers.account', $account_number)
            ->get();

        foreach ($journal_entries as $entries) {
            if ($entries->debit != null) {
                $deposits = $deposits + $entries->debit;
            }
            if ($entries->credit != null) {
                $credits = $credits + $entries->credit;
            }
        }

        return $credits - $deposits;
    }

    //Function to calculate amounts on more accounts belows
    public static function amount_on_more_accounts_belows($account_number)
    {
        $deposits = 0;
        $credits = 0;
        $journal_entries = DB::table('general_ledgers')
            ->select('general_ledgers.*')
            ->orderBy('general_ledgers.created_at', 'DESC')
            ->where('general_ledgers.account', $account_number)
            ->get();

        foreach ($journal_entries as $entries) {
            if ($entries->debit != null) {
                $deposits = $deposits + $entries->debit;
            }
            if ($entries->credit != null) {
                $credits = $credits + $entries->credit;
            }
        }

        return $deposits - $credits;
    }

    //Function to calculate amounts on adding general entries
    public static function calculate_balance_on_addition_of_ledgers($account_number)
    {

        // $sub_account = DB::table('sub_bank_accounts')
        // ->select('sub_bank_accounts.*')
        //     ->where('sub_bank_accounts.account_number', $account_number)
        //     ->where('sub_bank_accounts.bank_account_id', 151)
        //     ->first();

        // $more_accounts_below = DB::table('more_account_belows')
        // ->select('more_account_belows.*')
        //     ->where('more_account_belows.account_number', $account_number)->first();

        // if ($sub_account) {
        //     $result = CalculateBalances::amounts_on_sub_accounts($account_number);
        // } else if ($more_accounts_below) {
        //     $result = CalculateBalances::amount_on_more_accounts_belows($account_number);
        // } else {
            //LOGIC  TO DO  CHECK WHETHER IT IS A DEBIT OR CREDIT
            $specific_account_number = $account_number;
            //SEPARATING THE ACCOUNT TO GET PARTS OF AN ACCOUNT
            // $x = explode('-', $specific_account_number);
            // $chopped_account_number = $x[0] . '-' . $x[1];
            $first_digit = $account_number[0];
            $deposits = 0;
            $credits = 0;

            if ($first_digit == '3') {
                // ASSETS ACCOUNT
                $result = CalculateBalances::get_amounts_on_selected_account($account_number, 'assets');
                //ASSETS ACCOUNT
            } else if ($first_digit == '4') {
                //LIABILITIES
                $result = CalculateBalances::get_amounts_on_selected_account($account_number, 'liabilities');
                //LIABILITIES

            } else if ($first_digit == '2') {
                //EXPENSES
                $result = CalculateBalances::get_amounts_on_selected_account($account_number, 'expenses');
                //EXPENSES
            } else if ($first_digit == '1') {
                //REVENUE
                $result = CalculateBalances::get_amounts_on_selected_account($account_number, 'revenue');
                //REVENUE
            } else {
                // RESERVES ACCOUNT
                $result = CalculateBalances::get_amounts_on_selected_account($account_number, 'reserves');
                // RESERVES ACCOUNT
            }
        // }

        return $result;
    }

    public static function get_amounts_on_selected_account($account_number, $category)
    {
        $credits = 0;
        $deposits = 0;

        $journal_entries1 = DB::table('general_ledgers')
            ->select('id', 'details', 'account', 'debit', 'credit', 'created_at')
            ->orderBy('created_at', 'DESC')
            ->where('account', $account_number)
            ->get();

        foreach ($journal_entries1 as $entries1) {
            if ($entries1->debit != null) {
                $deposits = $deposits + $entries1->debit;
            }
            if ($entries1->credit != null) {
                $credits = $credits + $entries1->credit;
            }
        }

        if ($category == 'assets') {
            return ($deposits - $credits);
        } else if ($category == 'liabilities') {
            return ($credits - $deposits);
        } else if ($category == 'expenses') {
            return ($deposits - $credits);
        } else if ($category == 'revenue') {
            return ($credits - $deposits);
        } else if ($category == 'equity') {
            return ($credits - $deposits);
        }

    }

}
