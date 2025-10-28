<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LaravelLegends\EloquentFilter\Concerns\HasFilter;

class Category extends Model
{
    use HasFactory,HasFilter;

    public function educationalContent()
    {
        return $this->belongsTo(EducationalContent::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
