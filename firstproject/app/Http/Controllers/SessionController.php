<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SessionController extends Controller

{
    

    public function setuserdata(Request $request)
    {
        

         $user = Auth::user(); 

        /** @var \App\Models\User $user */

    $request->session()->put('user_data', [
        'id'    => $user->id,
        'name'  => $user->name,
        'email' => $user->email,
        'role'  => $user->getRoleNames()
    ]);

    return response()->json(['message' => 'Session stored successfully']);


    }

    public function getuserdata()
    {
        $userData = request()->session()->get('user_data', []);

        return response()->json(['user_data' => $userData], 200);
    }

    

    public function deleteuserdata()
    {
        request()->session()->forget('user_data');
        return response()->json(['message' => 'User data deleted successfully'], 200);
    }





}
