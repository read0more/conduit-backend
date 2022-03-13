<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'body'
    ];

    protected $attributes = [
        // 'tagList' => [],
    ];

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'article_tags');
    }
}
