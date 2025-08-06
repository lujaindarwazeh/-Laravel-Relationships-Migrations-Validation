<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\registerrequest;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\HTTP\Requests\loginrequest;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;


class AuthController extends Controller
{
    //

    public function register(registerrequest $request)
    {

        $user = new User();
        
        $user->password = bcrypt($request->password); 
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'user' => new UserResource($user),
        ], 200);






    }


    public function login(loginrequest $request)
    {

        if(!auth()->attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }


       /** @var \App\Models\User $user */
            $user = auth()->user();
       
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'User logged in successfully',
            'user' => new UserResource($user),
            'token' => $token,
        ], 200);
    }


public function logout(Request $request)
{
    /** @var \App\Models\User $user */
    $user = auth()->user();
    

    if ($user && $user->currentAccessToken()) {
        /** @var \Laravel\Sanctum\PersonalAccessToken $token */
        $token = $user->currentAccessToken();
        $token->delete();
    }

    return response()->json([
        'success' => true,
        'message' => 'User logged out successfully',
    ], 200);
}







}
