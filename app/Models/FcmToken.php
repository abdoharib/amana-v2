<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FcmToken extends Model
{
   protected $fillable = [
        'user_id',
        'token',
    ];

    public function tokenable(): MorphTo
    {
        return $this->morphTo();
    }
}
