<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Auth;

trait CheckIdIsMe
{
    function checkIdIsMe($id)
    {
        try {
            $me = Auth::user();
            return $me['id'] === $id;
        } catch (\Exception $e) {
            return false;
        }
    }
}
