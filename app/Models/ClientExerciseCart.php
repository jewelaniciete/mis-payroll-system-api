<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Exercise;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientExerciseCart extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'exercise_id', 'price'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
