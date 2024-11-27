<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(){
        $clients = Client::all();
        return response()->json($clients);
    }
}
