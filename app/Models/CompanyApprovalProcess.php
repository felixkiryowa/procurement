<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyApprovalProcess extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'module', 'approval_steps', 'created_by'
    ];
}
