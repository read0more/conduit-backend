<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'bio', 'image'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    protected $attributes = [
        'bio' => null,
        'image' => null,
    ];

    protected $appends = ['token'];

    protected function getTokenAttribute()
    {
        return $this->attributes['token'] ?? Auth::guard('api')->getTokenForRequest() ?? null;
    }

    protected function setTokenAttribute($token)
    {
        $this->attributes['token'] = $token;
    }

    public function articles()
    {
        return $this->hasMany('App\Models\Article');
    }
}