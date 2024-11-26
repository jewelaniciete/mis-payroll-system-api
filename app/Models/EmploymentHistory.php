<?php

namespace App\Models;

use App\Models\Staff;
use App\Models\Position;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmploymentHistory extends Model
{
    use HasFactory;

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }
}
