<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name' ,'description'];

    /**
     * Many-To-Many
     * User-Role Ranstion
     */
    public function users()
    {
        return $this->belongsToMany(User::class,'user_roles');
    }

    /**
     * Role-Permission Relation
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'role_permissions','role_id','permission_id');
    }
}
