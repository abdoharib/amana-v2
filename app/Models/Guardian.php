<?php

namespace App\Models;

use App\Traits\HasParentModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use LaravelLegends\EloquentFilter\Concerns\HasFilter;

class Guardian extends User
{
    use HasFactory, HasParentModel, Notifiable, HasFilter;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'password',
        'is_activated',
    ];

    public function kids()
    {
        return $this->hasMany(kid::class, 'guardian_id');
    }
    public function vaccinations()
    {
        return $this->hasMany(Vaccination::class, 'guardian_id');
    }
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope("guardian", function ($query) {
            $query->role('guardian');
        });

        static::created(function (Guardian $model) {
            if (!$model->hasRole("guardian")) {
                $model->assignRole("guardian");
            }
        });
    }
}
