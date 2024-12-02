<?php

namespace App\Models;

use App\Models\Staff;
use App\Models\Inventory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffCart extends Model
{
    use HasFactory;
    protected $fillable = ['staff_id', 'inventory_id', 'quantity', 'price'];


    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function product()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id', 'id');
    }
}
