<?php

namespace App\Models;

use App\Models\Staff;
use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SecurityQuesAndAns extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'staff_id', 'answer_1', 'answer_2', 'answer_3'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
