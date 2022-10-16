<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalBidAmount extends Model
{
    use HasFactory;

    protected $fillable = [
      'best_evaluated_bidder_id', 'amount', 'currency'
    ];
}
