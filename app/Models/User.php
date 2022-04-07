<?php

namespace App\Models;

use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

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

    protected $appends = ['token', 'favoriteArticleIds'];

    protected function getTokenAttribute()
    {
        return $this->attributes['token'] ?? null;
    }

    protected function setTokenAttribute($token)
    {
        $this->attributes['token'] = $token;
    }

    public function articles()
    {
        return $this->hasMany('App\Models\Article');
    }

    public function getFavoriteArticleIdsAttribute()
    {
        return $this->hasMany('App\Models\Favorite')->pluck('article_id');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
