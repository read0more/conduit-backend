<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilesController extends Controller
{
    public function get(User $user)
    {
        return response()->json(['profile' => $user], 200);
    }

    public function follow(User $user)
    {
        $follower = Auth::user();
        $follow = Follower::whereBelongsTo($user, 'user')->whereBelongsTo($follower, 'follower')->first();

        if ($follow) {
            $follow->delete();
        } else {
            $follow = new Follower;
            $follow->user()->associate($user);
            $follow->follower()->associate($follower);
            $follow->save();
        }

        return response()->json(['profile' => $user], 200);
    }
}
