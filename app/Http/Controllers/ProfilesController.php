<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    public function get(User $user)
    {
        return response()->json(['profile' => $user], 200);
    }
}
