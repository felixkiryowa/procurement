<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];
    
    protected static $logFillable = true;

    public function ledger_categories() {
        return $this->hasMany(LedgerCategory::class);
    }

    public function bank_accounts() {
        return $this->hasMany(BankAccount::class, 'ledger_id');
    }
}
