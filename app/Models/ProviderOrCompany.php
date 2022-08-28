<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class ProviderOrCompany extends Model
{
    use HasFactory, Notifiable;


    protected $fillable = [
        'email', 'secret_code_sent'
    ];
}
