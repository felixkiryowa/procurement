<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidsInvitations extends Model
{
    use HasFactory;

    protected $guarded = [];

    Protected $table = 'tender_notices';

    protected $fillable = [
        'name',
        'reference_number',
        'plan_id',
        'subject_id',
        'category_id',
        'method_id',
        'budget_amount',
        'winner_bid_price',
        'details',
        'display_start_date',
        'display_end_date',
        'type',
        'status',
        'document_url',
        'deadline',
        'updated_by',
        'created_by'
    ];

}
