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
        if ($request->accessCode !== env('ACCESS_CODE', '')) {
            return response()->json(['error' => 'Wrong access code'], 401);
        }

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

        $token = auth('api')->login($user);
        return $this->respondWithToken($token);
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized!'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function getAuthUser(Request $request)
    {
        $user = auth('api')->user();
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
        auth('api')->logout();
        return response()->json(['message'=>'Successfully logged out']);
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ])->cookie(\Config::get('constants.cookieName'), $token);
    }

}
