<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
Use Illuminate\Support\Facades\Log;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name' ,'description'];



     //model event
     public static function boot()
     {
         parent::boot();
 
         static::creating(function($item){
            Log::info('createing Event call..');
         });


         static::created(function($item) {            
            Log::info('Created event call: '.$item);
        });  
     }

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

    public function hasAccess($module ,$access)
    {
       // dd($module);
        foreach ($this->permissions as $permission) {
           if($permission->hasAccess($module ,$access))
           {
                return true;
           }
        }
    }
}
