<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoreAccountBelow extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'account_below_bank_account_id',
        'name',
        'account_number'
    ];

}
