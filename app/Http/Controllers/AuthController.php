<?php

namespace App\Http\Controllers;

use App\User;
use \Cookie;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public $loginAfterSignUp = true;

    public function signup(Request $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
        } catch(\Exception $e) {
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062){
                return response()->json(['error' => 'Duplicate Entry'], 409);
            } else {
                return response()->json(['error' => 'Unknown error'], 500);
            }
        }

        $token = auth()->login($user);
        return $this->respondWithToken($token);
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        \Log::debug($request);
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function getAuthUser(Request $request)
    {
        $user = auth()->user();
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'drinks' => $user->drinks->map(function($item) {
                return $item->only(['name', 'description', 'image', 'id']);
            })
        ];
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message'=>'Successfully logged out']);
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ])->cookie(\Config::get('constants.cookieName'), $token);
    }

}
