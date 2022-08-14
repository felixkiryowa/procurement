<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LedgerCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'ledger_id',
        'name',
        'item_code'
    ];
    
    protected static $logFillable = true;

    public function ledger() {
      return $this->belongsTo(Ledger::class);
    }

    public function bank_accounts() {
        return $this->hasMany(BankAccount::class, 'ledger_category_id');
    }
}
