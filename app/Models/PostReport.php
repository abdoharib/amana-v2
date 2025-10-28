<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelLegends\EloquentFilter\Concerns\HasFilter;

class PostReport extends Model
{
    use HasFilter;
    protected $fillable = [
        'post_id',
        'user_id',
        'reason',
        'custom_reason',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
