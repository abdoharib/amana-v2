<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LaravelLegends\EloquentFilter\Concerns\HasFilter;

class Advice extends Model
{
    use HasFactory,HasFilter;

    protected $fillable = [
        'title',
        'description',
        'image',
        'guardian_id',
        'kid_id',
    ];

    public function guardian()
    {
        return $this->belongsTo(Guardian::class, 'guardian_id');
    }

    public function kid()
    {
        return $this->belongsTo(Kid::class);
    }
}