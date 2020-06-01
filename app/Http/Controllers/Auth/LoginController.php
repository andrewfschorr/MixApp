<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\SocialProfile;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function credentials(Request $request)
    {
        return [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'isAdmin' => true,
        ];
    }

    /**
     * Redirect the user to the FB authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider(Request $request)
    {
        // if ($request->accessCode !== env('ACCESS_CODE', '')) {
        //     return response()->json(['error' => 'Wrong access code'], 401);
        // }
        return \Socialite::driver('facebook')->stateless()->redirect();
    }

    /**
     * Obtain the user information from FB.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(Request $request)
    {
        $facebookUser = \Socialite::driver('facebook')->stateless()->user();
        // do login stuff here
        // {
        //     user: {
        //       token: 'EAAEBlO9jGjMBAKfkfXOZBvlRIDrLamU1uhMmZCSgzQjjGgZC1im9IkCVDLM7NpgNfitdgCo324TQ6EMYmZAWJzaYoUuYcNjEHcfymhyKHi37nOaZCxuZCX08XiQL146b0wsQwaMyJ3Nx4izZCEytWq2ZBZAqGj6FaKPcZD',
        //       refreshToken: null,
        //       expiresIn: 5103377,
        //       id: '10100726355165361',
        //       nickname: null,
        //       name: 'Andrew Schorr',
        //       email: 'afs35mm@gmail.com',
        //       avatar: 'https://graph.facebook.com/v3.3/10100726355165361/picture?type=normal',
        //       user: {
        //         name: 'Andrew Schorr',
        //         email: 'afs35mm@gmail.com',
        //         id: '10100726355165361'
        //       },
        //       avatar_original: 'https://graph.facebook.com/v3.3/10100726355165361/picture?width=1920',
        //       profileUrl: null
        //     }
        //   }

        // $token = auth('api')->login($user);
        // return $this->respondWithToken($token);
        // return response()->json([
        //     'access_token' => $token,
        //     'token_type' => 'bearer',
        //     // 'expires_in' => auth('api')->factory()->getTTL() * 60
        // ]);

        $user = User::firstOrCreate([
            'email' => $facebookUser->email,
        ], [
            'name' => $facebookUser->name,
            'avatar' => $facebookUser->avatar,
        ]);

        $socialProfile = SocialProfile::create([
            'provider' => 'facebook',
            'fb_id' => $facebookUser->id,
            'user_id' => $user->id,
        ]);

        $user->social_id = $socialProfile->id;
        $user->save();

        $token = auth('api')->login($user);

        return [
            'token_type' => 'bearer',
            'access_token' => $token,
            'user' => $user
        ];
    }
}
