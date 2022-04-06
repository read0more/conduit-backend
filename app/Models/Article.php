<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'body', 'slug'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    // todo: 직접 바꾸는 식이 아니라 전체 필드에 대해서 camelcase로 바꾸는 방법 있는지 확인 필요
    protected $appends = ['createdAt', 'updatedAt', 'tagList', 'author', 'favoritesCount'];

    public function getCreatedAtAttribute()
    {
        return $this->attributes['created_at'] ?? null;
    }

    public function getUpdatedAtAttribute()
    {
        return $this->attributes['updated_at'] ?? null;
    }

    public function getTagListAttribute()
    {
        return $this->tags()->get();
    }

    public function getAuthorAttribute()
    {
        return $this->user()->get();
    }


    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'article_tags');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFavoritesCountAttribute()
    {
        return $this->hasMany('App\Models\Favorite')->count();
    }
}
