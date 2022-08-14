<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;
use Illuminate\Http\Request;

use Auth;
use App\Ledger;


class TrialBalanceExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    


    public function view(): View
    {
        $roles = Auth::user()->roles()->first();
        $role = $roles->name;

        $assetscode = '1000-5000';
        $liabilitiescode = '6000-10000';
        $expensescode = '11000-16000';
        $revenuecode = '17000-22000';
        $equitycode = '23000-27000';
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
        ->where('ledgers.item_code', 'like', $assetscode.'%' )
        ->get();



        $account_group_liabilities = Ledger::with(
                'ledger_categories',
                'ledger_categories.bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account.more_accounts_below'

            )
        ->where('ledgers.item_code', 'like', $liabilitiescode.'%' )
        ->get();


        $account_group_revenue = Ledger::with(
                'ledger_categories',
                'ledger_categories.bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account.more_accounts_below'

            )
        ->where('ledgers.item_code', 'like', $revenuecode.'%' )
        ->get();



        $account_group_expenses = Ledger::with(
                'ledger_categories',
                'ledger_categories.bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account.more_accounts_below'

            )
        ->where('ledgers.item_code', 'like', $expensescode.'%' )
        ->get();


        $account_group_equity = Ledger::with(
                'ledger_categories',
                'ledger_categories.bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account',
                'ledger_categories.bank_accounts.sub_bank_accounts.accounts_below_sub_account.more_accounts_below'

            )
        ->where('ledgers.item_code', 'like', $equitycode.'%' )
        ->get();
                   
           
        return view('chartaccounts.pdftrialbalance', [
            'account_group_assets' => $account_group_assets,
            'account_group_liabilities' => $account_group_liabilities,
            'account_group_expenses' => $account_group_expenses,
            'account_group_revenue' => $account_group_revenue,
            'account_group_equity' => $account_group_equity,
        ]);
    }
}
