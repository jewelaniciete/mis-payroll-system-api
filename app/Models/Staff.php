<?php

namespace App\Models;

use App\Models\Transaction;
use App\Models\EmployeePayroll;
use App\Models\EmploymentHistory;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Staff extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = ['email', 'password', 'firstname', 'lastname', 'address', 'gender', 'contact_no', 'is_active'];
    protected $hidden = ['password'];

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'staff_id');
    }

    public function history()
    {
        return $this->hasMany(EmploymentHistory::class, 'staff_id');
    }

    public function payroll()
    {
        return $this->hasMany(EmployeePayroll::class, 'staff_id');
    }
}
