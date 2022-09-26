<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyApprovalOrder extends Model
{
    use HasFactory;


    protected $fillable = [
        'company_id', 'module', 'user_step', 'user_id', 'created_by'
    ];
}
