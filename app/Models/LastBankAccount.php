<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LastBankAccount extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'sub_bank_account_id',
        'name',
        'account_number',
        'account_code',
    ];

    protected static $logFillable = true;

    public function below_bank_accounts_under_sub_account() {
        return $this->belongsTo(SubBankAccount::class);
    }

    public function more_accounts_below() {
        return $this->hasMany(MoreAccountBelow::class, 'account_below_bank_account_id');
    }
}
