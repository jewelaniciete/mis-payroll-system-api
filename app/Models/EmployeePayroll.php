<?php

namespace App\Models;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeePayroll extends Model
{
    use HasFactory;

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
