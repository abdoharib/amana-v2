<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelLegends\EloquentFilter\Concerns\HasFilter;
use Illuminate\Support\Facades\Storage;
class Story extends Model
{
    use HasFilter;
    protected $fillable = [
        'title',
        'content',
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
