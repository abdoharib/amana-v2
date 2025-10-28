<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoralValue extends Model
{
    protected $fillable = [
        'title',
        'image_path',
        'description',
        'created_at',
        'updated_at',
    ];

    public function getImagePathAttribute($value)
    {
        return asset('storage/' . $value);
    }
}
