<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
// use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\StoreRegisterRequest;


class AuthenticationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['authenticate','registerStore']]);
    }
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

        
        // $token = $user->createToken('access_token')->plainTextToken;

        // if(!auth()->check()){
        //     auth()->login($user);
        // }
        return response()->json(['success' => true]);
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
        

        // dd($credentials);
         if (! $token = auth()->attempt($credentials)) {
            return response()->json([
                'errors' => ['not_exists' => ['The provided credentials do not match our records.']]
            ], 401);
        }

        $token = JWTAuth::fromUser(auth()->user());
            // if (!auth()->attempt($credentials)) {
        //     return response()->json([
        //         'errors' => ['not_exists' => ['The provided credentials do not match our records.']]
        //     ],401);
        //     // $request->session()->regenerate();
        //     // return redirect()->intended('/post');
        // }
        $user = User::where('email',$request->input('email'))->first();
        // $token = $user->createToken('access_token')->plainTextToken;
        $user->access_token =  $token ;
        $user->save();
        // dd($token);
        return $this->respondWithToken($token,$user);
    //    return response()->json(['success' => true, 'access_token' => $token,'user_details' => $user]);
    }

    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                return response()->json(['token_absent'], $e->getStatusCode());

        }
        return response()->json(compact('user'));
    }


    public function me()
    {
        
        if ($this->guard()->user()->access_token) {
            return response()->json($this->guard()->user());
        }
        $this->guard()->logout();
        return response()->json(false);
    }

    public function logout(Request $request)
    {
        // dd(auth()->id(),Crypt::decryptString($request->user_id));
        $user = User::where('id', auth()->id())
                ->update(['access_token' => null]);

    
        auth()->logout();
        
        return response()->json(['success' => true]);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    protected function respondWithToken($token,$user)
    {
        // dd('SHIT');
        $expiration = $this->guard()->factory()->getTTL() * 60;
        return response()->json([
            'access_token' => $token,
            'user_id' => Crypt::encryptString($user->id)
        ]);
        // ->cookie('access_token', $token, $expiration)
        // ->cookie('user_id', Crypt::encryptString($user->id), $expiration);
    }

    public function guard()
    {
        return Auth::guard('api');
    }

}
