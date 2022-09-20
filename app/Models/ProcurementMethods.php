<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementMethods extends Model
{
    use HasFactory;

    protected $guarded = [];

    Protected $table = 'procurement_methods';

    protected $fillable = [
        'name'
    ];
}
