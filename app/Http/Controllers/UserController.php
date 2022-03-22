<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Cookie;

class UserController extends Controller
{
    /**
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'user' => 'required|array|min:3',
            'user.username' => 'required|unique:users,username',
            'user.email' => 'required|email|unique:users,email',
            'user.password' => 'required',
        ]);

        $userFileds = $request->all()['user'];
        $password = $userFileds['password'];
        $userFileds['password'] = Hash::make($password);

        $user = User::create($userFileds);
        $user->token = auth()->attempt(['email' => $userFileds['email'], 'password' => $password]);
        return response()->json(['user' => $user], 201);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'user' => 'required|array|min:2',
            'user.email' => 'required',
            'user.password' => 'required',
        ]);

        $userFileds = $request->user;
        $user = User::where('email', '=', $userFileds['email'])->firstOrFail();
        $user->token = auth()->attempt(['email' => $userFileds['email'], 'password' => $userFileds['password']]);

        if (!$user->token) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $expire = Carbon::now()->addSecond(3600);
        $tokenCookie = Cookie::create('token', $user->token, $expire->toCookieString());        

        return response()->json(['user' => $user])->withCookie($tokenCookie);
    }

    public function me(Request $request)
    {
        return response()->json(['user' => $request->user()]);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'user' => 'required|array|min:1',
            'user.username' => 'unique:users,username',
            'user.email' => 'email|unique:users,email',
        ]);

        $userFileds = $request->all()['user'];
        if ($userFileds['password'] ?? false) {
            $userFileds['password'] = Hash::make($userFileds['password']);
        }

        $user = User::find($request->user()['id']);
        $user->update($userFileds);
        return response()->json(['user' => $user], 201);
    }
}
