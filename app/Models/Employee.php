<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['city' ,'job' ,'salary' ,'joining_date' ,'user_id'];

    /**
     * Employee-User Relation
     */
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id')->select('id' ,'first_name' ,'last_name' ,'email' ,'phone');
    }

    /**
     * Employee-Task relation
     */
    public function tasks()
    {
        return $this->hasMany(Task::class ,'employee_id','id');
    }
}
