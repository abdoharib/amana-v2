<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use LaravelLegends\EloquentFilter\Concerns\HasFilter;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasFilter;
    use HasApiTokens;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guard_name = 'sanctum';
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'is_activated',
        'image'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function getRoleAttribute()
    {
        return $this->roles()->first()?->name ?? null; // Returns the first role name
    }
    public function advices()
    {
        if ($this->role == 'admin') {
            return Advice::select('*');
        }
        return $this->hasMany(Advice::class, 'guardian_id');
    }
    protected function password(): Attribute
    {
        return new Attribute(null, fn($val) => Hash::make($val));
    }
    public function kids()
    {
        if ($this->role == 'admin') {
            return Kid::select('*');
        }
        return $this->hasMany(Kid::class, 'guardian_id');
    }
    public function fcmTokens()
    {
        return $this->hasMany(FcmToken::class);
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    public function likedPosts()
    {
        return $this->belongsToMany(Post::class, 'post_user_like')->withTimestamps();
    }
    
    public static function boot()
    {
        parent::boot();

        static::saved(function ($user) {

            if ($user->roles()->exists()) { // Check if user has at least one role
                $roles = $user->roles()->pluck('id'); // Get assigned role IDs

                foreach ($roles as $role) {
                    DB::table("model_has_roles")->updateOrInsert(
                        [
                            'role_id' => $role,
                            'model_id' => $user->id,
                            'model_type' => 'App\Models\User'
                        ],
                        [
                            'role_id' => $role,
                            'model_id' => $user->id,
                            'model_type' => 'App\Models\User'
                        ]
                    );
                }
            }
        });
    }
}
