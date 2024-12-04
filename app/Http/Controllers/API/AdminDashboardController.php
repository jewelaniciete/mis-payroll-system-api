<?php

namespace App\Http\Controllers\API;

use App\Models\Client;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ExerciseTransaction;
use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{

    public function index(){
        $totalSales = Client::with('exerciseTransactions.exercise')->get();
        $salesByExercise = $totalSales->flatMap(function ($client) {
            return $client->exerciseTransactions->map(function ($transaction) {
                return $transaction->exercise;
            });
        })->groupBy('id')->map(function ($exercises) {
            return [
                'id' => $exercises->first()->id,
                'name' => $exercises->first()->name,
                'sales' => $exercises->sum(function ($exercise) {
                    return $exercise->price;
                }),
            ];
        });

        $totalGender = [
            'male' => $totalSales->where('gender', 'male')->count(),
            'female' => $totalSales->where('gender', 'female')->count(),
        ];

        $totalSalesCount = $salesByExercise->sum('sales');

        $sessionCount = $totalSales->map(function ($client) {
            return $client->exerciseTransactions->pluck('exercise.tag')->contains('session') ? 1 : 0;
        })->sum();

        $monthlyCount = $totalSales->map(function ($client) {
            return $client->exerciseTransactions->pluck('exercise.tag')->contains('monthly') ? 1 : 0;
        })->sum();

        $response = [
            'Sales_exercise' => $salesByExercise->values(),
            'total_gender' => [
                [
                    'male' => $totalGender['male'],
                    'female' => $totalGender['female'],
                ]
            ],
            'total_sales' => $totalSalesCount,
            'monthly_customer' => $monthlyCount,
            'session_customer' => $sessionCount,
        ];

        return response()->json($response);
    }
}
