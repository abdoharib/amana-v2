<?php

namespace App\Models;

use App\Traits\HasParentModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use LaravelLegends\EloquentFilter\Concerns\HasFilter;
use Spatie\Permission\Models\Role; 


class Admin extends User
{
    use HasFactory, HasParentModel,Notifiable,HasFilter;

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope("admin", function ($query) {
            $query->role('admin');
        });
        
        static::created(function (Admin $model) {
            
                $model->assignRole('admin');
             
        });
    }
}
