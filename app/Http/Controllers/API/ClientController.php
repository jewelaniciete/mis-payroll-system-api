<?php

namespace App\Http\Controllers\API;

use App\Models\Exercise;
use Illuminate\Http\Request;
use App\Models\ClientExerciseCart;
use Illuminate\Support\Facades\DB;
use App\Models\ClientExerciseOrder;
use App\Http\Controllers\Controller;
use App\Models\ClientExerciseOrderItem;

class ClientController extends Controller
{
    public function add_to_cart(Request $request){
        $validated = $request->validate([
            'exercises' => 'required|array',
            'exercises.*.exercise_id' => 'required|exists:exercises,id',
        ]);

        foreach ($validated['exercises'] as $exercise){
            $service = Exercise::find($exercise['exercise_id']);

            if (!$service) {
                return response()->json(['error' => "Exercise with ID {$exercise['exercise_id']} not found"], 404);
            }

            $price = $service->price;

            $cartItem = ClientExerciseCart::where('exercise_id', $service->id)
                ->where('client_id', auth()->id())
                ->first();

             if ($cartItem) {
                return response()->json(['message' => 'Exercise was already in cart'], 200);
            }
            else{
                ClientExerciseCart::create([
                    'client_id' => auth()->id(),
                    'exercise_id' => $service->id,
                    'price' => $price,
                ]);
            }

        }

        return response()->json(['message' => 'Exercise added to cart'  ], 201);
    }

    public function remove_from_cart(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'exercise_id' => 'required|exists:exercises,id',
        ]);

        $serviceId = $request->input('exercise_id');

        $cartItem = ClientExerciseCart::where('client_id', $user->id)
            ->where('exercise_id', $serviceId)
            ->first();

        if (!$cartItem) {
            return response()->json(['error' => 'Item not found in cart'], 404);
        }

        $cartItem->delete();

        return response()->json([
            'message' => 'Exercise removed from cart successfully',
        ], 200);
    }

    // public function view_cart($clientId)
    // {
    //     $cartItems = ClientExerciseCart::with('exercise')
    //         ->where('client_id', $clientId)
    //         ->get();

    //     return response()->json(['data' => $cartItems], 200);
    // }

    public function exercise_checkout(Request $request)
    {

        $user = auth()->user();

        $cartItems = ClientExerciseCart::where('client_id', $user->id)->with('exercise')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Your cart is empty'], 400);
        }

        // Start a database transaction
        DB::beginTransaction();

        try {

            $order = ClientExerciseOrder::create([
                'client_id' => $user->id,
                'total_amount' => $cartItems->sum(fn($item) => $item->exercise->price),
                'status' => 'paid'
            ]);

            foreach($cartItems as $item){
                ClientExerciseOrderItem::create([
                    'exercise_order_id' => $order->id,
                    'exercise_id' => $item->exercise_id,
                    'price' => $item->exercise->price,
                ]);
            }

            ClientExerciseCart::where('client_id', $user->id)->delete();

            DB::commit();

            return response()->json([
                'message' => 'Checkout successful',
                'order_id' => $order->id,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Checkout failed', 'error' => $e->getMessage()], 400);
        }
    }
}
