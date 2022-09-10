<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementCategories extends Model
{
    use HasFactory;

    protected $guarded = [];

    Protected $table = 'procurement_categories';

    protected $fillable = [
        'name'
    ];
}
