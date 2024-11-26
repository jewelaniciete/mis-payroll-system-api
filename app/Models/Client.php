<?php

namespace App\Models;

use App\Models\Transaction;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = ['email', 'password', 'firstname', 'lastname', 'address', 'gender', 'contact_no', 'is_active'];
    protected $hidden = ['password'];

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'client_id');
    }
}
