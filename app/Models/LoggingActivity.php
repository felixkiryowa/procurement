<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoggingActivity extends Model
{
    use HasFactory;

    protected $fillable = [
       'subject', 'details', 'performed_by'
    ];
}
