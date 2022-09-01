<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_type_id',
        'organisationName',
        'procurementCategory',
        'briefDescription',
        'userName',
        'password',
        'email',
        'companyPhoneNumber',
        'alternativeCompanyPhoneNumber',
        'secretQuestion',
        'secretAnswer',
        'challengeAnswer',
        'country',
        'registrationNumber',
        'taxId',
        'codeSentToEmail',
        'firstName',
        'lastName',
        'address',
        'city',
        'originCountry',
        'region',
        'zip_code',
        'company_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
