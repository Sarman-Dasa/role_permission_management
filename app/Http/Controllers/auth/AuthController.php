<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\AccountVerifyMail;
use App\Notifications\PasswordResetMail;
use App\Notifications\WelcomeMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * User Registration 
     * store user data in database
     * @param \Illuminate\Http\Request  $request
     * @return json response 
     */
    public function register(Request $request)
    {
        $request->validate([
            'first_name'            =>  'required|string|min:3|max:30',
            'last_name'             =>  'required|string|min:3|max:30',
            'email'                 =>  'required|email|unique:users,email',
            'password'              =>  'required|confirmed',
            'phone'                 =>  'required|numeric|unique:users,phone',
            'role_id'               =>  'required|array|exists:roles,id',
        ]);

        $user = User::create($request->only(['first_name' ,'last_name' ,'email' ,'phone'])
            + [
                'password'              =>  Hash::make($request->password),
                'email_verify_token'    =>  Str::random(64),
            ]);
        
        $user->roles()->attach($request->role_id);
        //$user->notify(new WelcomeMail());
        //$user->notify(new AccountVerifyMail($user));

        return ok("Account Created Successfully");
    }

    /**
     * verify user account 
     * @param string token
     * @return json response 
     */
    public function verifyAccount($token)
    {
 
         $user = User::where('email_verify_token',$token)->first();
         if($user)
         {
             $user->update([
                 'email_verify_token'    =>  null,
                 'email_verified_at'     => Carbon::now(),
                 'is_active'             =>  true,
             ]);
             return ok('Account Verify Successfuly');
         }else{
             return ok('Account Already Verified');
         }
    }
    
    /**
     * user login 
     * @param \Illuminate\Http\Request  $request
     * @return json response 
     */
    public function login(Request $request)
    {
 
         $request->validate([
             'email'    =>  'required|email|exists:users,email',
             'password' =>  'required',
         ]);
 
         if(Auth::attempt(['email' => $request->email, 'password' => $request->password , 'is_active' => true]))
         {
             $user   =   auth()->user();
             $token  =  $user->createToken('API TOKEN')->plainTextToken;
             return ok('User Login Successfully',$token);
         }
         return ok('Invalid Email & Password');
    }

    
    /**
     * @func forgotPassword
     * @param Request $request 
     * @return json response
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' =>  'required|email|exists:users,email'
        ]);

        $user = User::where('email',$request->email)->first();
        $data  = PasswordReset::updateOrCreate(
            ['email'    =>  $request->email],
            [
                'email'         =>  $request->email,
                'token'         =>  Str::random(64),
                'expired_at'    =>  now()->addDays(2),
            ]);

        $user['token'] = $data->token;

        $user->notify(new PasswordResetMail($user));
        return ok('Password Reset Link Send Successfully',[
            'token' => $data->token,
        ]);
    }

    /**
     * @func resetPassword
     * @description 'chnage password via password reset token'
     * @param Request $request
     * @return json response
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'                 =>  'required|exists:password_resets,token',
            'password'              =>  'required|min:8|max:12',
            'password_confirmation' =>  'required|same:password', 
        ]);

        $passwordReset = PasswordReset::where('token',$request->token)->first();
        //dd($passwordReset->email);
        if($passwordReset->expired_at >= now())
        {
            User::updateOrCreate(
                ['email' => $passwordReset->email],
                [
                    'password' => Hash::make($request->password),
                ]);
            
                $passwordReset->where('email',$passwordReset->email)->delete();
            
            return ok('Password Reset Successfully');
        }
        return error('Password Reset Token Expired!!!',type:'unauthenticated');
    }
}
