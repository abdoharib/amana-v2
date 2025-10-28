<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use LaravelLegends\EloquentFilter\Concerns\HasFilter;

class Item extends Model
{
    use HasFactory,HasFilter;

    protected $fillable = ['title', 'image_path', 'category_id','educational_content_id'];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function itemDetails()
    {
        return $this->hasMany(ItemDetail::class);
    }

    public function getImagePathAttribute($value)
    {
        return url(Storage::url($value));
    }
}
