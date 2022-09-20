<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementPlanDetails extends Model
{
    use HasFactory;

    protected $guarded = [];

    Protected $table = 'procurement_plan_details';

    protected $fillable = [
        'plan_id', 'category_id' , 'brief', 'method_id' , 'amount', 'C', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'updated_by', 'created_by'
    ];
}
