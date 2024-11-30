<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Staff;
use App\Models\Client;
use App\Models\Exercise;
use App\Models\Position;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\EmployeePayroll;
use App\Models\EmployeeAttendance;
use App\Http\Controllers\Controller;
use App\Http\Resources\StaffShowResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ClientShowResource;
use App\Http\Responses\ValidationResponse;
use App\Http\Resources\ExerciseShowResource;
use App\Http\Resources\PositionShowResource;
use App\Http\Resources\InventoryShowResource;
use App\Http\Resources\EmployeePayrollResource;
use App\Http\Resources\EmployeeAttendanceResource;

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

    public function soft_delete_inventories(Request $request, $id){
        //
    }

    public function hard_delete_inventories(Request $request, $id){
        //
    }

    public function restore_inventories(Request $request, $id){
        //
    }

    public function show_staff_attendances(){
        $attendances = EmployeeAttendance::with('staff')->get();

        return EmployeeAttendanceResource::collection($attendances);
    }

    public function store_staff_attendances(Request $request, $id){
        $staff = Staff::find($id);
        if(!$staff){
            return response()->json([
                'message' => 'Staff not found'
            ], 404);
        }

        $today = Carbon::now()->format('Y-m-d');

        if($request->date != $today){
            return response()->json([
                'message' => 'Date is not today'
            ], 400);
        }

        $attendance = EmployeeAttendance::where('staff_id', $staff->id)
                        ->where('date', $today)->first();

        if($attendance){
            return response()->json([
                'message' => 'Attendance already filled'
            ], 400);
        }


        $validator = Validator::make($request->all(), [
            'staff_id' => 'required',
            'date' => 'required',
            'attendance' => 'required'
        ]);

        if ($validator->fails()) {
            return new ValidationResponse($validator->errors());
        }


        $attendance = EmployeeAttendance::create([
            'staff_id' => $request->staff_id,
            'date' => $request->date,
            'attendance' => $request->attendance,
        ]);

        return response()->json([
            'data' => new EmployeeAttendanceResource($attendance),
            'message' => 'Attendance created successfully'
        ], 201);
    }

    public function edit_staff_attendances(Request $request, $id){
        $attendance = EmployeeAttendance::find($id);
        if(!$attendance){
            return response()->json([
                'message' => 'Attendance not found'
            ], 404);
        }

        return response()->json([
            'data' => new EmployeeAttendanceResource($attendance),
            'message' => 'Attendance retrieved successfully'
        ], 200);
    }

    public function update_staff_attendances(Request $request, $id){
        $attendance = EmployeeAttendance::find($id);

        if(!$attendance){
            return response()->json([
                'message' => 'Attendance not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'attendance' => 'required'
        ]);

        if ($validator->fails()) {
            return new ValidationResponse($validator->errors());
        }

        $attendance->update([
            'attendance' => $request->attendance,
        ]);

        return response()->json([
            'data' => new EmployeeAttendanceResource($attendance),
            'message' => 'Attendance updated successfully'
        ], 200);
    }

    public function soft_delete_staff_attendances(Request $request, $id){
        //
    }

    public function hard_delete_staff_attendances(Request $request, $id){
        //
    }

    public function restore_staff_attendances(Request $request, $id){
        //
    }

    public function show_staff_payrolls(){
        $payroll = EmployeePayroll::with('staff')->get();

        return response()->json([
            'data' => EmployeePayrollResource::collection($payroll),
            'message' => 'Payroll retrieved successfully',
        ], 200);
    }

    public function store_staff_payrolls(Request $request, $id){
        $staff = Staff::find($id);
        if(!$staff){
            return response()->json([
                'message' => 'Staff not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'staff_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $id = $staff->id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $query = EmployeeAttendance::query()
        ->when($id, function ($query, $id) {
            return $query->where('id', $id);
        })
        ->when($start_date, function ($query, $start_date) {
            return $query->whereDate('date', '>=', $start_date);
        })
        ->when($end_date, function ($query, $end_date) {
            return $query->whereDate('date', '<=', $end_date);
        });

        // $filteredData = $query->with('staff.position')->get();
        // $present_days = $query->count();

        $whole_days = $query->clone()->where('attendance', 'present')->count();

        $half_days = $query->clone()->where('attendance', 'halfday')->count();

        $present_day = $whole_days + ($half_days / 2);

        $salary = 450 * $present_day;

        // Additional Incomes
        $overtime = $request->over_time;
        $yearly_bonus = $request->yearly_bonus;
        $sales_comission = $request->sales_comission;
        $incentives = $request->incentives;

        $net_income = $salary + $overtime + $yearly_bonus + $sales_comission + $incentives;

        // Deductions
        $sss = (0.02 * $net_income) / 2;
        $pag_ibig = (0.02 * $net_income) / 2;
        $philhealth = (0.02 * $net_income) / 2;

        $total_deductions = $sss + $pag_ibig + $philhealth;

        $final_salary = $net_income - $total_deductions;

        $payroll = EmployeePayroll::create([
            'staff_id' => $staff->id,
            'present_day' => $present_day,
            'salary' => $salary,
            'over_time' => $overtime,
            'yearly_bonus' => $yearly_bonus,
            'sales_comission' => $sales_comission,
            'incentives' => $incentives,
            'sss' => $sss,
            'pag_ibig' => $pag_ibig,
            'philhealth' => $philhealth,
            'net_income' => $net_income,
            'total_deductions' => $total_deductions,
            'final_salary' => $final_salary,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);

        return response()->json([
            'data' => new EmployeePayrollResource($payroll),
            'message' => 'Payroll retrieved successfully'
        ]);
    }

}
