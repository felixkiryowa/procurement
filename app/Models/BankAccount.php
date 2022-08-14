<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'ledger_category_id',
        'name',
        'account_number',
        'ledger_id'
    ];

    protected static $logFillable = true;

    public  function ledger_category() {
        return $this->belongsTo(LedgerCategory::class);
    }

    public function bank_account_category() {
        return $this->hasMany(BankAccountCategory::class, 'bank_account_id');
    }

    public function ledgers() {
        return $this->belongsTo(Ledger::class);
    }

    public function sub_bank_accounts() {
        return $this->hasMany(SubBankAccount::class, 'bank_account_id');
    }
}
