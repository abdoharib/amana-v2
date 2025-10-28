<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ItemDetail extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'audio_path', 'image_path', 'item_id'];
    public function item()
    {
        return $this->belongsTo(Item::class);
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
