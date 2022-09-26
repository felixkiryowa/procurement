<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalRequestStatus extends Model
{
    use HasFactory;


    protected $fillable = [
        'procurement_plan_detail_id', 'approver_id', 'status', 'reason','module'
    ];
}
