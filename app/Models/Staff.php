<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Staff extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = ['email', 'password', 'firstname', 'lastname', 'address', 'gender', 'contact_no', 'is_active'];
    protected $hidden = ['password'];
}
