<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BestEvaluatedBidder extends Model
{
    use HasFactory;


    protected $fillable = [
      'tender_notice_id', 'user_id', 'company_id', 'reason', 
      'best_evaluated_bidder', 'provider', 
      'submitted_bid_id'
    ];
}
