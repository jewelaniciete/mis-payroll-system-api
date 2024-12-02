<?php

namespace App\Models;

use App\Models\Transaction;
use App\Models\ClientExerciseCart;
use App\Models\SecurityQuesAndAns;
use Laravel\Passport\HasApiTokens;
use App\Models\ClientExerciseOrder;
use App\Models\ClientInventoryCart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable
{
    use SoftDeletes;
    use HasFactory, HasApiTokens;

    protected $fillable = ['email', 'firstname', 'lastname', 'address', 'gender', 'contact_no', 'is_active'];

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'client_id');
    }
    public function cart()
    {
        return $this->hasMany(ClientExerciseCart::class);
    }

    public function orders()
    {
        return $this->hasMany(ClientExerciseOrder::class);
    }

    public function inventory_cart()
    {
        return $this->hasMany(ClientInventoryCart::class);
    }

}
