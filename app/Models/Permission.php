<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name' ,'description'];

    /**
     * Many-To-Many
     * Permission-Module Relation
     */
     public function modules()
     {
        return $this->belongsToMany(Module::class,'module_permissions' ,'permission_id' ,'module_id')->withPivot('add_access' ,'delete_access' ,'view_access' ,'update_access');
     }

     /**
      * Permission-Role Relation
      */
      public function roles()
      {
         return $this->belongsToMany(Role::class,'role_permissions','permission_id','role_id');
      }

}
