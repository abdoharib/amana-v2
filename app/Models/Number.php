<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Number extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'image_path',
        'audio_path',
    ];
    public function getImagePathAttribute($value)
    {
        return url(Storage::url($value));
    }
    public function getAudioPathAttribute($value)
    {
        return url(Storage::url($value));
    }
}
