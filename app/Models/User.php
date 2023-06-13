<?php

namespace App\Models;

use App\Models\Task;
use App\Models\TaskComment;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable implements JWTSubject

{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $name = 'username';
    protected $fillable = [
        'username',
        'email',
        'password',
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
    protected $appends = ['profile_picture'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    // public function tasks(){
    //     return $this->hasMany(Task::class);
    // }

    public function getProfilePictureAttribute()
    {
        if ($this->image_name) {
            return asset('storage/profile_pictures/' . $this->hash_image_name);
        } else {
            return asset('storage/defaults/no-profile.png');
        }
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function tasks(){
        return $this->belongsToMany(Task::class,'task_user');
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }
    // public function getProfilePictureUrl()
    // {
    //     if ($this->image_name) {
    //         return asset('storage/profile_pictures/' . $this->hash_image_name);
    //     } else {
    //         return asset('storage/defaults/no-profile.png');
    //     }
    // }
}
