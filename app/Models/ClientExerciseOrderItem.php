<?php

namespace App\Models;

use App\Models\Exercise;
use App\Models\ClientExerciseOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientExerciseOrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['exercise_order_id', 'exercise_id', 'price'];

    public function order()
    {
        return $this->belongsTo(ClientExerciseOrder::class);
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }

}
