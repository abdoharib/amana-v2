<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelLegends\EloquentFilter\Concerns\HasFilter;

class Vaccination extends Model
{
    use HasFilter;
    protected $fillable = [
        'vaccination_date',
        'vaccination_time',
        'guardian_id',
        'status',
    ];

    public function guardian()
    {
        return $this->belongsTo(Guardian::class, 'guardian_id');
    }
}
