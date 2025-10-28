<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use LaravelLegends\EloquentFilter\Concerns\HasFilter;

class PrayerContent extends Model
{
    use HasFactory,HasFilter;

    protected $fillable = ['content', 'audio_file', 'prayer_id'];

    public function getAudioFileAttribute($value)
    {
        return url(Storage::url($value));
    }
}
