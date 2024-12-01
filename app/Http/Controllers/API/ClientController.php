<?php

namespace App\Http\Controllers\API;

use App\Models\Client;
use App\Models\Exercise;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\ClientExerciseCart;
use App\Models\SecurityQuesAndAns;
use Illuminate\Support\Facades\DB;
use App\Models\ClientExerciseOrder;
use App\Models\ClientInventoryCart;
use App\Http\Controllers\Controller;
use App\Models\ClientInventoryOrder;
use App\Models\ClientExerciseOrderItem;
use App\Models\ClientInventoryOrderItem;

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

    public function inventory_add_to_cart(Request $request){
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:inventories,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        foreach ($validated['items'] as $item) {
            $product = Inventory::find($item['product_id']);

            if (!$product) {
                return response()->json(['error' => "Product with ID {$item['product_id']} not found"], 404);
            }

            $basePrice = $product->price;
            $quantity = $item['quantity'];

            // Calculate the total price based on the quantity
            $price = $basePrice * $quantity;

            // Check if the product is already in the cart
            $cartItem = ClientInventoryCart::where('inventory_id', $product->id)
                ->where('client_id', auth()->id())
                ->first();

            if ($cartItem) {
                // Update quantity if the product is already in the cart
                $cartItem->quantity += $item['quantity'];
                $cartItem->price = $basePrice * $cartItem->quantity;
                $cartItem->save();
            } else {
                // Add the product to the cart
                ClientInventoryCart::create([
                    'client_id' => auth()->id(),
                    'inventory_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);
            }
        }

        return response()->json(['message' => 'Product added to cart successfully'], 201);
    }

    public function inventory_remove_item(Request $request){
        $user = auth()->user(); // Assumes user is authenticated

        // Validate input
        $request->validate([
            'product_id' => 'required|exists:inventories,id',
        ]);

        $productId = $request->input('product_id');

        $cartItem = ClientInventoryCart::where('client_id', $user->id)
            ->where('inventory_id', $productId)
            ->first();

        if (!$cartItem) {
            return response()->json(['error' => 'Item not found in cart'], 404);
        }

        // Remove the cart item
        $cartItem->delete();

        return response()->json([
            'message' => 'Item removed from cart successfully',
        ], 200);
    }

    public function inventory_checkout(Request $request){

        $user = auth()->user(); // Assumes user is authenticated

        $cartItems = ClientInventoryCart::where('client_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Your cart is empty'], 400);
        }

        // Start a database transaction
        DB::beginTransaction();

        try {

            $order = ClientInventoryOrder::create([
                'client_id' => $user->id,
                'total_amount' => $cartItems->sum(function ($item) {
                    return $item->quantity * $item->product->price;
                }),
                'status' => 'pending' // Status can be pending, paid, etc.
            ]);

            foreach ($cartItems as $item) {
                if ($item->product->quantity < $item->quantity) {
                    throw new \Exception("Insufficient stock for product: {$item->product->name}");
                }


                ClientInventoryOrderItem::create([
                    'order_id' => $order->id,
                    'inventory_id' => $item->inventory_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price * $item->quantity,
                ]);

                $item->product->decrement('quantity', $item->quantity);
            }

            ClientInventoryCart::where('client_id', $user->id)->delete();

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

    public function add_security_answer(Request $request){
        $validated = $request->validate([
            'answer_1' => 'required',
            'answer_2' => 'required',
            'answer_3' => 'required',
        ]);

        if($validated['answer_1'] == null || $validated['answer_2'] == null || $validated['answer_3'] == null){
            return response()->json(['error' => 'Answer cannot be empty'], 400);
        }

        $client = Client::find(auth()->id());

        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }

        $client_answer = SecurityQuesAndAns::create([
            'client_id' => $client->id,
            'answer_1' => $validated['answer_1'],
            'answer_2' => $validated['answer_2'],
            'answer_3' => $validated['answer_3'],
        ]);

        return response()->json([
            'message' => 'Security question answers added successfully',
        ], 201);
    }

}
