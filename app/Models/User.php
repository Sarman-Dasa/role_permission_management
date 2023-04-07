<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Jobs\SendNotificationMail;
use App\Notifications\AccountVerifyMail;
use App\Notifications\WelcomeMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'email_verify_token',
        'is_active',
        'email_verified_at',
        'birthdate',
        'device_token',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Many-To-Many 
     * User-Role Relation
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class,'user_roles','user_id','role_id');
    }

    /**
     * One-To-One
     * User-Employee Relation
     */
    public function employee()
    {
        return $this->hasOne(Employee::class,'user_id','id');
    }

    //
    public function hasAccess($module,$access)
    {
       
        foreach ($this->roles as $role) 
        {
            if($role->name == "Super-Admin")
            {
                return true;
            }
            else if($role->hasAccess($module ,$access))
            {
                return true;
            }
        }
    }

    //send welcome and verify mail
    public static function boot()
    {
        parent::boot();

        static::creating(function()
        {
            Log::info('User Creating');
        });

        static::created(function($user){
            if(!$user->is_active)
                SendNotificationMail::dispatch($user);
            log::channel('customelog')->info('Mail send via model:- '.$user->is_active);
        });
    }
}
