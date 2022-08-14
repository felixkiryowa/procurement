<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use HasFactory;


    protected $fillable = [
        'district', 'name', 'title', 'duty_station', 'bank', 'account_number', 'telephone_number'
    ];
}
