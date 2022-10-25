<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenderDocuments extends Model
{
    use HasFactory;

    protected $fillable = [
        'tender_id',
        'document_url',
        'document_type',
        'tracking_number',
        'updated_by',
        'created_by'
    ];
}

