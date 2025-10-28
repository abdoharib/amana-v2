<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProphetStory extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image_path',
       
    ];

    public function details()
    {
        return $this->hasMany(ProphetStoryDetail::class);
    }


    public function getImagePathAttribute($value)
    {
        return url(Storage::url($value));
    }

    public function getAudioPathAttribute($value)
    {
        return url(Storage::url($value));
    }
}
