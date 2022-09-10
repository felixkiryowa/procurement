<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementPlan extends Model
{
    use HasFactory;

    protected $guarded = [];

    Protected $table = 'procurement_plans';

    protected $fillable = [
        'organization_id', 'financial_year_start' , 'financial_year_end' , 'title', 'details', 'document_url','period', 'status', 'updated_by', 'created_by'
    ];
}
