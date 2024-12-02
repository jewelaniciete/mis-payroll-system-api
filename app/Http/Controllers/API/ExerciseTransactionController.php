<?php

namespace App\Http\Controllers\API;

use App\Models\Staff;
use App\Models\Client;
use App\Models\Exercise;
use Illuminate\Http\Request;
use App\Models\ExerciseTransaction;
use App\Http\Controllers\Controller;

class ExerciseTransactionController extends Controller
{
    public function store(Request $request) {
        $validated = $request->validate([
            'client_id' => 'required',
            'exercise_id' => 'required',
            'isMainPlan' => 'required',
            'expire_date' => 'required',
            'transaction_code' => 'required',
        ]);

        $client = Client::find($request->client_id);
        if (!$client) {
            return response()->json([
                'message' => 'Client not found'
            ], 404);
        }

        $exercise = Exercise::find($request->exercise_id);
        if (!$exercise) {
            return response()->json([
                'message' => 'Exercise not found'
            ], 404);
        }
        $price = $exercise->price;

        $instructor = Staff::where('id', $request->instructor_id)->
            where('position_id', '2')->first();

        if (!$instructor) {
            return response()->json([
                'message' => 'Instructor not found'
            ], 404);
        }

        $exercise_transaction = ExerciseTransaction::create([
            'client_id' => $client->id,
            'exercise_id' => $exercise->id,
            'instructor_id' => $instructor->id,
            'isMainPlan' => $request->isMainPlan,
            'expire_date' => $request->expire_date,
            'price' => $price,
            'transaction_code' => $request->transaction_code,
        ]);

        return response()->json([
            'message' => 'Exercise transaction created successfully',
            'data' => $exercise_transaction,
        ], 201);
    }

    public function show(){
        $records = Client::with(['exerciseTransactions.exercise', 'exerciseTransactions.instructor'])->get();

        $modifiedRecords = $records->map(function ($client) {
            return [
                'client_name' => $client->firstname . ' ' . $client->lastname,
                'email' => $client->email,
                'gender' => $client->gender,
                'address' => $client->address,
                'contact_no' => $client->contact_no,
                'transactions' => $client->exerciseTransactions->map(function ($transaction) {
                    return [
                        'exercise_name' => $transaction->exercise->name,
                        'tag' => $transaction->exercise->tag,
                        'instructor_name' => $transaction->instructor->firstname . ' ' . $transaction->instructor->lastname,
                        'price' => $transaction->price,
                        'transaction_code' => $transaction->transaction_code,
                        'isMainPlan' => $transaction->isMainPlan,
                        'expire_date' => $transaction->expire_date
                    ];
                }),
                'total_price' => $client->exerciseTransactions->sum('price'),
            ];
        });

        return response()->json([
            'data' => $modifiedRecords,
            'message' => 'Records retrieved successfully'
        ]);
    }

}
