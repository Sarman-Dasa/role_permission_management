<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = ['name' ,'description'];

    /**
     * Many-to-Many
     * Module-Permission Relation
     */

     public function permissions()
     {
        return $this->belongsToMany(Permission::class,'module_permissions','module_id','permission_id')->withPivot('add_access' ,'delete_access' ,'view_access' ,'update_access');
     }

}
