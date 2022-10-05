<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmittedBid extends Model
{
    use HasFactory;


    protected $fillable = [
       'tender_notice_id', 'user_id', 'amount', 'brief_description', 'start_date', 'end_date', 'currency', 'status'
    ];
}
