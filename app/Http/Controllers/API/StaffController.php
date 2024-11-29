<?php

namespace App\Http\Controllers\API;

use App\Models\Client;
use App\Models\Inventory;
use App\Models\StaffCart;
use App\Models\StaffOrder;
use Illuminate\Http\Request;
use App\Models\StaffOrderItem;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ClientShowResource;
use App\Http\Responses\ValidationResponse;

class StaffController extends Controller
{
    public function show_clients(){
        $clients = Client::all();
        return ClientShowResource::collection($clients);
    }

    public function store_clients(Request $request){
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'contact_no' => 'required'
        ]);

        if ($validator->fails()) {
            return new ValidationResponse($validator->errors());
        }

        $client = Client::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'address' => $request->address,
            'gender' => $request->gender,
            'contact_no' => $request->contact_no
        ]);

        return response()->json([
            'data' => new ClientShowResource($client),
            'message' => 'Client created successfully'
        ], 201);
    }

    public function edit_clients(Request $request, $id){
        $client = Client::find($id);
        if(!$client){
            return response()->json([
                'message' => 'Client not found'
            ], 404);
        }

        return response()->json([
            'data' => new ClientShowResource($client),
            'message' => 'Client retrieved successfully'
        ], 200);
    }

    public function update_clients(Request $request, $id){
        $client = Client::find($id);

        if(!$client){
            return response()->json([
                'message' => 'Client not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'contact_no' => 'required'
        ]);

        if ($validator->fails()) {
            return new ValidationResponse($validator->errors());
        }

        $client->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'address' => $request->address,
            'gender' => $request->gender,
            'contact_no' => $request->contact_no
        ]);

        return response()->json([
            'data' => new ClientShowResource($client),
            'message' => 'Client updated successfully'
        ], 200);
    }

    public function soft_delete_clients(Request $request, $id){
        //
    }

    public function hard_delete_clients(Request $request, $id){
        //
    }

    public function restore_clients(Request $request, $id){
        //
    }

    public function add_to_cart(Request $request){
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
            $cartItem = StaffCart::where('inventory_id', $product->id)
                ->where('staff_id', auth()->id())
                ->first();

            if ($cartItem) {
                // Update quantity if the product is already in the cart
                $cartItem->quantity += $item['quantity'];
                $cartItem->price = $basePrice * $cartItem->quantity;
                $cartItem->save();
            } else {
                // Add new item to the cart
                StaffCart::create([
                    'staff_id' => auth()->id(),
                    'inventory_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);
            }
        }

        return response()->json(['message' => 'Products added to cart successfully'], 200);
    }

    public function remove_item(Request $request)
    {
        $user = $request->user(); // Assumes user is authenticated

        // Validate input
        $request->validate([
            'product_id' => 'required|exists:inventories,id',
        ]);

        $productId = $request->input('product_id');

        // Find the cart item
        $cartItem = StaffCart::where('staff_id', $user->id)
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

    public function checkout(Request $request)
    {
        $user = auth()->user();

        // Fetch user's cart items
        $cartItems = StaffCart::where('staff_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Your cart is empty'], 400);
        }

        DB::beginTransaction();
        try {
            // Create an order
            $order = StaffOrder::create([
                'staff_id' => $user->id,
                'total_amount' => $cartItems->sum(function ($item) {
                    return $item->quantity * $item->product->price;
                }),
                'status' => 'pending', // Status can be pending, paid, etc.
            ]);

            // Create order items and adjust product stock
            foreach ($cartItems as $item) {
                if ($item->product->quantity < $item->quantity) {
                    throw new \Exception("Insufficient stock for product: {$item->product->name}");
                }

                StaffOrderItem::create([
                    'order_id' => $order->id,
                    'inventory_id' => $item->inventory_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                // Reduce product stock
                $item->product->decrement('quantity', $item->quantity);
            }

            // Clear user's cart
            StaffCart::where('staff_id', $user->id)->delete();

            DB::commit();

            return response()->json([
                'message' => 'Checkout successful',
                'order_id' => $order->id,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

}
