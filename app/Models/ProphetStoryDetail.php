<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProphetStoryDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'prophet_story_id',
        'title',
        'content',
        'image_path',
        'audio_path',
    ];

    public function prophetStory()
    {
        return $this->belongsTo(ProphetStory::class);
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
