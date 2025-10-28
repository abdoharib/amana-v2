<?php

namespace App\Models;

use App\Traits\HasParentModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use LaravelLegends\EloquentFilter\Concerns\HasFilter;

class Kid extends Model
{
    
    use HasFactory,HasFilter;

    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'sex',
        'weight',
        'height',
        'guardian_id',
    ];

    public function guardian()
    {
        return $this->belongsTo(Guardian::class,'guardian_id');
    }
}
