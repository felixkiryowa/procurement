<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubBankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_account_id',
        'name',
        'account_number',
    ];

    protected static $logFillable = true;

    public function bank_account_category() {
        return $this->belongsTo(BankAccountCategory::class);
    }

    public function accounts_below_sub_account() {
        return $this->hasMany(LastBankAccount::class);
    }

    public function bank_account() {
        return $this->belongsTo(BankAccount::class);
    }
}
