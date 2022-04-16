<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body'
    ];

    protected $appends = ['createdAt', 'updatedAt'];

    public function getCreatedAtAttribute()
    {
        return $this->attributes['created_at'] ?? null;
    }

    public function getUpdatedAtAttribute()
    {
        return $this->attributes['updated_at'] ?? null;
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
