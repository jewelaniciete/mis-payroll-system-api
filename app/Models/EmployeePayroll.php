<?php

namespace App\Models;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeePayroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'present_day',
        'total_salary',
        'whole_day_salary',
        'half_day_salart',
        'overtime',
        'yearly_bonus',
        'sales_comission',
        'incentives',
        'sss',
        'pag_ibig',
        'philhealth',
        'net_income',
        'total_deductions',
        'final_salary',
        'start_date',
        'end_date',
        'pay_date',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
