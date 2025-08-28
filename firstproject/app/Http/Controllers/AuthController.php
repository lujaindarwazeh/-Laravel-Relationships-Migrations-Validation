<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\registerrequest;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\HTTP\Requests\loginrequest;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


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


    public function deleteuser(Request $request)
    {
       
       /** @var \App\Models\User $user */
            $user = auth()->user();

        if ($user) {
            $user->tokens()->delete(); 
            $user->delete(); 
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }
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

        activity('auth')
        ->causedBy($user) 
        ->withProperties([
           
            'name'=>$user->name,
            'email'=>$user->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ])->tap(function ($activity)use ($user)  {
            $activity->event = 'login';
            $activity->subject_id = $user->id;
            $activity->subject_type = get_class($user);
        
        })
        ->log('User log in');






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



    
        activity('auth')
        ->causedBy($user) 
        ->withProperties([
           
            'name'=>$user->name,
            'email'=>$user->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ])->tap(function ($activity) use ($user) {
            $activity->event = 'logout';
            $activity->subject_id = $user->id;
            $activity->subject_type = get_class($user);
            
        })
        ->log('User log out');

    return response()->json([
        'success' => true,
        'message' => 'User logged out successfully',
    ], 200);
}







}
