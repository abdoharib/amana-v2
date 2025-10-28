<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelLegends\EloquentFilter\Concerns\HasFilter;

class Post extends Model
{

    use HasFilter;
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'media',
        'is_published',
        'is_pinned',
        'published_at'
    ];

    protected $casts = [
        'media' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(PostCategory::class, 'post_post_category');
    }

    public function reports()
    {
        return $this->hasMany(PostReport::class);
    }
    public function likes()
    {
        return $this->belongsToMany(User::class, 'post_user_like')->withTimestamps();
    }

    public function isLikedBy($user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }
}
