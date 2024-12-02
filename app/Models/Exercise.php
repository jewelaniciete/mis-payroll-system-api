<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'tag',
        'short_description',
        'image'
    ];

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'exercise_id');
    }
}
