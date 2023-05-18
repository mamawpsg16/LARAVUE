<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRegisterRequest;
// use Laravel\Sanctum\Sanctum;

class AuthenticationController extends Controller
{

    // public function shit(){
    //     $users = User::all();
        
    //     return response()->json($users);
    // }

    public function register()
    {
       return response()->json('register');
    }

 
    public function registerStore(StoreRegisterRequest $request)
    {
        $user = User::create([
            'username'   => $request->input('username'),
            'email'       => $request->input('email'),
            'password'    => bcrypt($request->input('password'))
        ]);

        $token = $user->createToken('access_token')->plainTextToken;

        // if(!auth()->check()){
        //     auth()->login($user);
        // }
        return response()->json(['user' => $user, 'success' => true,  'access_token' => $token]);
    }

    // public function login()
    // {
    //    return view('authentication.login');
    // }

    

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (!auth()->attempt($credentials)) {
            return response()->json([
                'errors' => ['not_exists' => ['The provided credentials do not match our records.']]
            ],422);
            // $request->session()->regenerate();
            // return redirect()->intended('/post');
        }
        $user = User::where('email',$request->input('email'))->first();
        $token = $user->createToken('access_token')->plainTextToken;
        // dd($token);
       return response()->json(['success' => true, 'access_token' => $token]);
    }


    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        
        return response()->json(['success' => true]);
    }

}
