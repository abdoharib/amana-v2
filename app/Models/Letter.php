<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Letter extends Model
{
    use HasFactory;
    protected $fillable = [
        'letter',
        'word',
        'image_path',
        'audio_path',
        'word_audio',
    ];

    public function getImagePathAttribute($value)
    {
        return url(Storage::url($value));
    }
    public function getWordAudioAttribute($value)
    {
        return url(Storage::url($value));
    }
    public function getAudioPathAttribute($value)
    {
        return url(Storage::url($value));
    }
}
