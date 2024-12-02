<?php

namespace App\Models;

use App\Models\Inventory;
use App\Models\ClientInventoryOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientInventoryOrderItem extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'inventory_id', 'quantity', 'price'];

    public function order()
    {
        return $this->belongsTo(ClientInventoryOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Inventory::class,'inventory_id', 'id');
    }
}
