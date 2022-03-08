<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        $userFileds['password'] = Hash::make($userFileds['password']);

        $user = User::create($userFileds);
        return response()->json(['user' => $user], 201);
        // return response()->json($request->user(), 201);
    }
}
