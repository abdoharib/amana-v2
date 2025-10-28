<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CharityWork extends Model
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
