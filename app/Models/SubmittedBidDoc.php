<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmittedBidDoc extends Model
{
    use HasFactory;

    protected $fillable = [
      'submitted_bid_id','document','tracking_number','created_by','last_updated_by'
    ];
}
