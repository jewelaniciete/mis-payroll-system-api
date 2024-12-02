<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ClientInventoryOrderItem;

class ClientInventoryOrder extends Model
{
    protected $fillable = ['client_id', 'total_amount', 'status'];

    public function items()
    {
        return $this->hasMany(ClientInventoryOrderItem::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

}
