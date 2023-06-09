<?php

namespace App\Http\Controllers;

use App\Exports\ExportUser;
use App\Imports\UserImport;
use App\Models\User;
use App\Traits\ListingApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use myDate;
use PDF;

class UserController extends Controller
{
    use ListingApiTrait;

    /**
     * Display a listing of the user.
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $this->ListingValidation();
    
        $query = User::query();
        $searchable_fields = ['first_name' , 'last_name','email' ,'phone']; 
        $data = $this->filterSearchPagination($query,$searchable_fields);

        if(request()->start_date && request()->end_date)
        {
            return Excel::download(new ExportUser(request()->start_date,request()->end_date),'user.csv');
        }
        
        if($request->has('import_data'))
        {
           Excel::import(new UserImport ,$request->file('import_data'));
           return ok("data import successfully");
        }

        if($request->pdf)
        {
            $users = $data['query']->get();
            $user = PDF::loadView('userPdf',compact('users'));
           return $user->download("user.pdf");
        }
        return ok('User list',[
            'users' =>  $data['query']->get(),
            'count' =>  $data['count'],
        ]);
    }

    /**
     * Update the specified user in database.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        $id = $user->id;
        $request->validate([
            'first_name'            =>  'required|string|min:3|max:30',
            'last_name'             =>  'required|string|min:3|max:30',
            'email'                 =>  'required|email|unique:users,email,' . $id . ',id',
            'password'              =>  'required|confirmed',
            'phone'                 =>  'required|numeric|unique:users,phone,' . $id . ',id',
            'role_id'               =>  'required|array|exists:roles,id',
        ]);


        $user->update($request->only(['first_name', 'last_name', 'email', 'phone'])
            + [
                'password'              =>  Hash::make($request->password),
            ]);

        $user->roles()->sync($request->role_id);

        return ok('user data updated successfuly');
    }

    /**
     * Display the specified user.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        $user = User::with('roles', 'roles.permissions')->findOrFail($id);
        return ok('User profile', $user);
    }

    /**
     * logout the specified user.
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        // $user = auth()->user()->tokens();
        // $user->delete();
        myDate::logout();
    }

    /**
     * change the specified user password.
     *@param \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password'      =>  'required|current_password',
            'password'              =>  'required|min:8|max:12',
            'password_confirmation' =>  'required|same:password',
        ]);

        $user = auth()->user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return ok('Password changed successfully');
    }

     /**
     * Remove the specified user from database.
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $user = auth()->user();
        $user->delete();

        return ok("Account deleted successfully");
    }
}
