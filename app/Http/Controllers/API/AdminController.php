<?php

namespace App\Http\Controllers\API;

use App\Models\Staff;
use App\Models\Client;
use App\Models\Exercise;
use App\Models\Position;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StaffShowResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ClientShowResource;
use App\Http\Responses\ValidationResponse;
use App\Http\Resources\ExerciseShowResource;
use App\Http\Resources\PositionShowResource;
use App\Http\Resources\InventoryShowResource;

class AdminController extends Controller
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

    public function show_staffs(){
        $staffs = Staff::with('position')->get();
        return StaffShowResource::collection($staffs);
    }

    public function store_staffs(Request $request){
        $validator = Validator::make($request->all(), [
            'position_id' => 'required',
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

        $staff = Staff::create([
            'position_id' => $request->position_id,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'address' => $request->address,
            'gender' => $request->gender,
            'contact_no' => $request->contact_no
        ]);

        return response()->json([
            'data' => new StaffShowResource($staff),
            'message' => 'Staff created successfully'
        ], 201);
    }

    public function edit_staffs(Request $request, $id){
        $staff = Staff::find($id);
        if(!$staff){
            return response()->json([
                'message' => 'Staff not found'
            ], 404);
        }

        return response()->json([
            'data' => new StaffShowResource($staff),
            'message' => 'Staff retrieved successfully'
        ], 200);
    }

    public function update_staffs(Request $request, $id){
        $staff = Staff::find($id);

        if(!$staff){
            return response()->json([
                'message' => 'Staff not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'position_id' => 'required',
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

        $staff->update([
            'position_id' => $request->position_id,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'address' => $request->address,
            'gender' => $request->gender,
            'contact_no' => $request->contact_no
        ]);

        return response()->json([
            'data' => new StaffShowResource($staff),
            'message' => 'Staff updated successfully'
        ], 200);
    }

    public function soft_delete_staffs(Request $request, $id){
        //
    }

    public function hard_delete_staffs(Request $request, $id){
        //
    }

    public function restore_staffs(Request $request, $id){
        //
    }

    public function show_exercises(){
        $exercises = Exercise::all();
        return ExerciseShowResource::collection($exercises);
    }

    public function store_exercises(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required',
            'plan' => 'required'
        ]);

        if ($validator->fails()) {
            return new ValidationResponse($validator->errors());
        }

        $exercise = Exercise::create([
            'name' => $request->name,
            'price' => $request->price,
            'plan' => $request->plan
        ]);

        return response()->json([
            'data' => new ExerciseShowResource($exercise),
            'message' => 'Exercise created successfully'
        ], 201);
    }

    public function edit_exercises(Request $request, $id){
        $exercise = Exercise::find($id);
        if(!$exercise){
            return response()->json([
                'message' => 'Exercise not found'
            ], 404);
        }

        return response()->json([
            'data' => new ExerciseShowResource($exercise),
            'message' => 'Exercise retrieved successfully'
        ], 200);
    }

    public function update_exercises(Request $request, $id){
        $exercise = Exercise::find($id);

        if(!$exercise){
            return response()->json([
                'message' => 'Exercise not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required',
            'plan' => 'required'
        ]);

        if ($validator->fails()) {
            return new ValidationResponse($validator->errors());
        }

        $exercise->update([
            'name' => $request->name,
            'price' => $request->price,
            'plan' => $request->plan
        ]);

        return response()->json([
            'data' => new ExerciseShowResource($exercise),
            'message' => 'Exercise updated successfully'
        ], 200);
    }

    public function soft_delete_exercises(Request $request, $id){
        //
    }

    public function hard_delete_exercises(Request $request, $id){
        //
    }

    public function restore_exercises(Request $request, $id){
        //
    }

    public function show_positions(){
        $positions = Position::all();
        return PositionShowResource::collection($positions);
    }

    public function store_positions(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return new ValidationResponse($validator->errors());
        }

        $position = Position::create([
            'name' => $request->name
        ]);

        return response()->json([
            'data' => new PositionShowResource($position),
            'message' => 'Position created successfully'
        ], 201);
    }

    public function edit_positions(Request $request, $id){
        $position = Position::find($id);
        if(!$position){
            return response()->json([
                'message' => 'Position not found'
            ], 404);
        }

        return response()->json([
            'data' => new PositionShowResource($position),
            'message' => 'Position retrieved successfully'
        ], 200);
    }

    public function update_positions(Request $request, $id){
        $position = Position::find($id);

        if(!$position){
            return response()->json([
                'message' => 'Position not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return new ValidationResponse($validator->errors());
        }

        $position->update([
            'name' => $request->name
        ]);

        return response()->json([
            'data' => new PositionShowResource($position),
            'message' => 'Position updated successfully'
        ], 200);
    }

    public function soft_delete_positions(Request $request, $id){
        //
    }

    public function hard_delete_positions(Request $request, $id){
        //
    }

    public function restore_positions(Request $request, $id){
        //
    }

    public function show_inventories(){
        $inventories = Inventory::all();
        return InventoryShowResource::collection($inventories);
    }

    public function store_inventories(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'short_description' => 'required',
            'quantity' => 'required',
            'price' => 'required'
        ]);

        if ($validator->fails()) {
            return new ValidationResponse($validator->errors());
        }

        $inventory = Inventory::create([
            'item_code' => uniqid(),
            'name' => $request->name,
            'type' => $request->type,
            'short_description' => $request->short_description,
            'quantity' => $request->quantity,
            'price' => $request->price
        ]);

        return response()->json([
            'data' => new InventoryShowResource($inventory),
            'message' => 'Inventory created successfully'
        ], 201);
    }

    public function edit_inventories(Request $request, $id){
        $inventory = Inventory::find($id);
        if(!$inventory){
            return response()->json([
                'message' => 'Inventory not found'
            ], 404);
        }

        return response()->json([
            'data' => new InventoryShowResource($inventory),
            'message' => 'Inventory retrieved successfully'
        ], 200);
    }

    public function update_inventories(Request $request, $id){
        $inventory = Inventory::find($id);

        if(!$inventory){
            return response()->json([
                'message' => 'Inventory not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'short_description' => 'required',
            'quantity' => 'required',
            'price' => 'required'
        ]);

        if ($validator->fails()) {
            return new ValidationResponse($validator->errors());
        }

        $inventory->update([
            'name' => $request->name,
            'type' => $request->type,
            'short_description' => $request->short_description,
            'quantity' => $request->quantity,
            'price' => $request->price
        ]);

        return response()->json([
            'data' => new InventoryShowResource($inventory),
            'message' => 'Inventory updated successfully'
        ], 200);
    }

}
