<?php

namespace App\Models;

use App\Models\EmploymentHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Position extends Model
{
    use HasFactory;

    public function history()
    {
        return $this->hasMany(EmploymentHistory::class, 'position_id');
    }
}
