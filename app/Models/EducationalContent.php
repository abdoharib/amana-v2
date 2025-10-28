<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use LaravelLegends\EloquentFilter\Concerns\HasFilter;

class EducationalContent extends Model
{
    use HasFactory,HasFilter;
    protected $fillable = [
        'title',
        'description',
        'image_path',
        'type',
        'education_stage_id',
    ];
    public function educationStage()
    {
        return $this->belongsTo(EducationStage::class);
    }
    public function getImagePathAttribute($value)
    {
        return url(Storage::url($value));
    }
}
