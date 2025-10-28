<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Prayer extends Model
{

    use HasFactory;

    protected $fillable = ['title', 'image_path'];

    public function contents()
    {
        return $this->hasMany(PrayerContent::class);
    }

    public function getImagePathAttribute($value)
    {
        return url(Storage::url($value));
    }
}
