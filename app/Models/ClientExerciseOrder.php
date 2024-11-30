<?php

namespace App\Models;

use App\Models\Client;
use App\Models\ClientExerciseOrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientExerciseOrder extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'total_amount', 'status'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(ClientExerciseOrderItem::class);
    }
}
