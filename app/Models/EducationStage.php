<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationStage extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'image_path',
    ];
    public function educationalContent()
    {
        return $this->hasMany(EducationalContent::class);
    }
    public function getImagePathAttribute($value)
    {
       return url(Storage::url($value));
    }
}
