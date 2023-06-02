<?php

namespace App\Models;

use App\Models\User;
use App\Models\Module;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function modulePermissions()
    {
        return $this->belongsToMany(Module::class, 'role_module_permission')
            ->withPivot('permission_id');
    }
}
