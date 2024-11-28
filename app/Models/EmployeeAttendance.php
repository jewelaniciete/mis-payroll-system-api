<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeAttendance extends Model
{
    use HasFactory;

    protected $fillable = ['staff_id', 'date', 'attendance'];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
