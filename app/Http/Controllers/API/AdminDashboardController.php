<?php

namespace App\Http\Controllers\API;

use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ExerciseTransaction;
use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{

    public function index(){
        // $totalSales = ExerciseTransaction::with('exercise', 'client')->get();

        // return response()->json([
        //     $totalSales
        // ]);
        // $totalSales = ExerciseTransaction::with('exercise')
        // ->select('exercise_id', DB::raw('sum(price) as total_sales'))
        // ->groupBy('exercise_id')
        // ->get();

        // $formattedSales = $totalSales->map(function($transaction) {
        //     return [
        //         'id' => $transaction->exercise_id,
        //         'name' => $transaction->exercise->name,  // Assuming the Exercise model has a 'name' field
        //         'total_sales' => $transaction->total_sales
        //     ];
        // });

        // return response()->json($formattedSales);

        $totalSales = ExerciseTransaction::with(['exercise', 'client'])
    ->select('exercise_id', 'client_id', DB::raw('sum(price) as total_sales'))
    ->groupBy('exercise_id', 'client_id')
    ->get();

$formattedSales = [];
$totalGender = [
    'male' => 0,
    'female' => 0
];

foreach ($totalSales as $transaction) {
    // Ensure exercise exists
    $exerciseName = $transaction->exercise ? $transaction->exercise->name : 'Unknown Exercise';
    $gender = $transaction->client ? $transaction->client->gender : 'unknown';
    $totalSalesAmount = $transaction->total_sales; // Total sales for this transaction

    // Initialize result array for each exercise if not already initialized
    if (!isset($formattedSales[$exerciseName])) {
        $formattedSales[$exerciseName] = [
            'id' => $transaction->exercise_id,
            'name' => $exerciseName,
            'male_sales' => 0,
            'female_sales' => 0
        ];
    }

    // Update male or female sales based on the client gender
    if ($gender == 'male') {
        $formattedSales[$exerciseName]['male_sales'] += $totalSalesAmount;
        $totalGender['male']++;
    } elseif ($gender == 'female') {
        $formattedSales[$exerciseName]['female_sales'] += $totalSalesAmount;
        $totalGender['female']++;
    }
}

// Prepare the final response format
$response = [
    'Sales_exercise' => array_values($formattedSales),  // Group exercises and their sales
    'total_gender' => [$totalGender]  // Include the total male and female count
];

return response()->json($response);
    }
}
