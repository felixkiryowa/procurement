<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class GeneralLedger extends Model
{
    use HasFactory;


    protected $fillable = [
      'details', 'account', 'debit', 'credit','reference','requested_by', 'category'
    ];

    public function getPrevAmount($account, $start, $end){


      $early_journal_entries = DB::table('general_ledgers')
            ->select('general_ledgers.*')
            ->whereDate('general_ledgers.created_at', '<', $start)
            ->where('general_ledgers.account', $account)
            ->get();

        $account_journal_entries = GeneralLedger::whereBetween('general_ledgers.created_at', [
                $start, $end,
        ])->where('general_ledgers.account', $account)
        ->get();

        $all_account_journal_entries = GeneralLedger::whereBetween('general_ledgers.created_at', [
            $start, $end,
        ])->where('general_ledgers.account', $account)
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

        return $Prev_amount_on_account;



    }


    public function getClosingAmount($account, $start, $end){


        $early_journal_entries = DB::table('general_ledgers')
              ->select('general_ledgers.*')
              ->whereDate('general_ledgers.created_at', '<', $start)
              ->where('general_ledgers.account', $account)
              ->get();

          $account_journal_entries = GeneralLedger::whereBetween('general_ledgers.created_at', [
                  $start, $end,
          ])->where('general_ledgers.account', $account)
          ->get();

          $all_account_journal_entries = GeneralLedger::whereBetween('general_ledgers.created_at', [
              $start, $end,
          ])->where('general_ledgers.account', $account)
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

          if($credits > $debits){
            $Prev_amount_on_account = $Prev_amount_on_account * -1;
          }

        //  return $Prev_amount_on_account;

        if($Prev_amount_on_account > $debits1){

            $amount_on_account =    $Prev_amount_on_account + $debits1 - $credits1;

        }else{

        $amount_on_account =  $debits1 - $credits1;

        }

        return $amount_on_account;



      }

      public function getActivityAmount($account, $start, $end){


        $early_journal_entries = DB::table('general_ledgers')
              ->select('general_ledgers.*')
              ->whereDate('general_ledgers.created_at', '<', $start)
              ->where('general_ledgers.account', $account)
              ->get();

          $account_journal_entries = GeneralLedger::whereBetween('general_ledgers.created_at', [
                  $start, $end,
          ])->where('general_ledgers.account', $account)
          ->get();

          $all_account_journal_entries = GeneralLedger::whereBetween('general_ledgers.created_at', [
              $start, $end,
          ])->where('general_ledgers.account', $account)
          ->get();

          $debits1 = 0;
          $credits1 = 0;
          $credits = 0;
          $debits = 0;

          foreach ($all_account_journal_entries as $entries) {
              if ($entries->debit != null) {
                  $debits = $debits + $entries->debit;
              }
          }

          foreach ($all_account_journal_entries as $entries) {
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

         return $Prev_amount_on_account;





      }

}
