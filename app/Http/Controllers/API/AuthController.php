<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Admin;
use App\Models\Staff;
use App\Models\Client;
use Illuminate\Http\Request;
use Laravel\Passport\Passport;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\AdminResource;
use App\Http\Resources\StaffResource;
use App\Http\Responses\ErrorResponse;
use App\Http\Resources\ClientResource;
use App\Http\Responses\SuccessResponse;
use Illuminate\Support\Facades\Validator;
use App\Http\Responses\ValidationResponse;
use Illuminate\Contracts\Auth\Authenticatable;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return new ValidationResponse($validator->errors());
        }

        $credentials = request(['email', 'password','remember_me']);

        $staffs = Staff::where([
            ['email','=',$credentials['email']],
            ['is_active','=',1]
        ])->get();

        $auth_user = $this->findUserByPassword($staffs, $credentials['password']);

        if(!$auth_user) //check client next
        {
            return $this->loginClient($request);
        }

        if (!Auth::guard('api-staff')->setUser($auth_user))
            return (new ErrorResponse)->unauthorize();

        $staff = Auth::guard('api-staff')->user();

        if((int)($credentials['remember_me']) == 1)  Passport::personalAccessTokensExpireIn(\Carbon\Carbon::now()->addDays(30));
        else Passport::personalAccessTokensExpireIn(Carbon::now()->addDays(1));

        $tokenResult = $staff->createToken('Staff Token',['staff_user']);
        $token = $tokenResult->token;

        $token->save();
        return new SuccessResponse(
            StaffResource::make($auth_user),
            [
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => $tokenResult->token->expires_at->toDateTimeString(),
            ]
        );
    }

    public function loginClient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $credentials = request(['email', 'password','remember_me']);

        $clients = Client::where('email',$credentials['email'])->get();
        $auth_user = $this->findUserByPassword($clients, $credentials['password']);

        if(!$auth_user) //check admin next
        {
            return $this->loginAdmin($request);
        }

        if (!Auth::guard('api-client')->setUser($auth_user)) {
            return (new ErrorResponse())->unauthorize();
        }

        $client = Auth::guard('api-client')->user();

        if((int)($credentials['remember_me']) == 1)  Passport::personalAccessTokensExpireIn(\Carbon\Carbon::now()->addDays(30));
        else Passport::personalAccessTokensExpireIn(Carbon::now()->addDays(1));

        $tokenResult = $client->createToken('Client Token', ['client_user']);
        $token = $tokenResult->token;

        $token->save();
        return new SuccessResponse(
            ClientResource::make($client),
        [
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => $tokenResult->token->expires_at->toDateTimeString(),
        ]);
    }

    public function loginAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $credentials = request(['email', 'password','remember_me']);

        $admins = Admin::where('email',$credentials['email'])->get();
        $auth_user = $this->findUserByPassword($admins, $credentials['password']);

        if(!$auth_user) return new ErrorResponse(message: 'Wrong Username/Password combination', code: Response::HTTP_UNAUTHORIZED);

        if (!Auth::guard('api-admin')->setUser($auth_user)) {
            return (new ErrorResponse())->unauthorize();
        }

        $admin = Auth::guard('api-admin')->user();

        if((int)($credentials['remember_me']) == 1)  Passport::personalAccessTokensExpireIn(\Carbon\Carbon::now()->addDays(30));
        else Passport::personalAccessTokensExpireIn(Carbon::now()->addDays(1));

        $tokenResult = $admin->createToken('Admin Token', ['admin_user']);
        $token = $tokenResult->token;

        $token->save();
        return new SuccessResponse(
            AdminResource::make($admin),
        [
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => $tokenResult->token->expires_at->toDateTimeString(),
        ]);
    }

    public function findUserByPassword(Collection $users, string $password): ?Authenticatable
    {
        foreach($users as $user){
            if(Hash::check($password ,$user->password)){
                return $user;
            }
        }
        return null;
    }

    public function auth(Request $request)
    {
        $user = auth()->user();
        if(!$user) return new ErrorResponse(message: 'Unauthenticated', code: Response::HTTP_UNAUTHORIZED);
        $user_type = check_user_type();

        $resources = [
            'staff' => StaffResource::class,
            'employee' => EmployeeResource::class,
            'admin' => AdminResource::class,
        ];

        if (!isset($resources[$user_type])) {
            return new ErrorResponse(message: 'Invalid user type', code: Response::HTTP_BAD_REQUEST);
        }

        return new SuccessResponse(
            $resources[$user_type]::make($user),
            []
        );
    }

    private function check_user_type($user)
    {
        if ($user->is_admin) {
            return 'admin';
        } elseif ($user->is_staff) {
            return 'staff';
        } else {
            return 'client';
        }
    }

    public function logout(Request $request)
    {
        try {
            $token = $request->user()->token();
            if($token) $token->revoke();
            $cookie = cookie()->forget('access_token'); //cookie in frontend
            session()->invalidate();
            return new SuccessResponse(message: 'Successfully logged out', cookie: $cookie);
        } catch (\Exception $e){
            return new ErrorResponse(message: 'Error Occured', code : Response::HTTP_BAD_REQUEST, e: $e);
        }
    }
}
