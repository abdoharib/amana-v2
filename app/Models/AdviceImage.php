<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AdviceImage extends Model
{
    use HasFactory;

    public function getImagePathAttribute($value)
    {
        return url(Storage::url($value));
    }
}
