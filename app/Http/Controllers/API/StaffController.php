<?php

namespace App\Http\Controllers\API;

use App\Models\Client;
use Illuminate\Http\Request;
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
}
