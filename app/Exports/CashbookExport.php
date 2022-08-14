<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;
use Illuminate\Http\Request;

use Auth;
use App\Models\GeneralLedger;


class CashbookExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $start_date, $end_date, $account;

	public function __construct(String  $start_date,String $end_date, String $account)
	{
	$this->start_date = $start_date;
	$this->end_date = $end_date;
    $this->account = $account;
	}

    public function view(): View
    {




        $early_journal_entries = DB::table('general_ledgers')
            ->select('general_ledgers.*')
            ->whereDate('general_ledgers.created_at', '<', $this->start_date)
            ->where('general_ledgers.account', $this->account)
            ->get();

        $account_journal_entries = GeneralLedger::whereBetween('general_ledgers.created_at', [
                $this->start_date, $this->end_date,
        ])->where('general_ledgers.account', $this->account)
        ->get();

        $all_account_journal_entries = GeneralLedger::where('general_ledgers.account', $this->account)
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

        $amount_on_account =  $debits1 - $credits1;
        $Prev_amount_on_account =  $debits - $credits;



        return view('chartofaccounts.excelcashbook', [
            'account_journal_entries' => $account_journal_entries,
            'Prev_amount_on_account' => $Prev_amount_on_account,
            'amount_on_account' => $amount_on_account,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'account' => $this->account,
        ]);
    }
}
