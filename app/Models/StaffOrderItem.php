<?php

namespace App\Models;

use App\Models\Inventory;
use App\Models\StaffOrder;
use Illuminate\Database\Eloquent\Model;

class StaffOrderItem extends Model
{

    protected $fillable = ['order_id', 'inventory_id', 'quantity', 'price'];

    public function order()
    {
        return $this->belongsTo(StaffOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Inventory::class,'inventory_id', 'id');
    }
}
