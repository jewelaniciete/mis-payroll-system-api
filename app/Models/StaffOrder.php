<?php

namespace App\Models;

use App\Models\Staff;
use App\Models\StaffOrderItem;
use Illuminate\Database\Eloquent\Model;

class StaffOrder extends Model
{
    protected $fillable = ['staff_id', 'total_amount', 'status'];

    public function items()
    {
        return $this->hasMany(StaffOrderItem::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
