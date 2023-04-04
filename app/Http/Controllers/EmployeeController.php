<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Traits\ListingApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    use ListingApiTrait;
    /**
     * Display a listing of the employees.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {

        $this->ListingValidation();

        $query = Employee::query()->with(['user', 'tasks']);

        // $query = Employee::query()->with(['user','tasks:id,title,description,employee_id']); // get a specific column data & employee_id is required other wise task data show empty
        //$query = Employee::query();

        $searchable_fields = ['city', 'job', 'salary', 'joining_date'];

        $data = $this->filterSearchPagination($query, $searchable_fields);

        // $query = $query->whereHas('user',function ($query) use ($request) {
        //     $query->where('email', 'like', '%' . $request->email . '%');
        // });

        $searchable_fields = ['first_name', 'last_name', 'email', 'phone'];

        //   $query = $query->whereHas('user',function ($query) use ($request) {
        //             $query->where('email', 'like', '%' . $request->usersearch . '%')
        //             ->orWhere('first_name','like','%'.$request->usersearch.'%')
        //             ->orWhere('phone','like','%'.$request->usersearch.'%');
        // });

        $query = $query->whereHas('user', function ($query) use ($request, $searchable_fields) {
            $query = $query->where(function ($query) use ($request, $searchable_fields) {
                foreach ($searchable_fields as $searchable_field) {
                    $query->orWhere($searchable_field, 'like', '%' . $request->usersearch . '%');
                }
            });
        });

        $data['count'] =  $query->count();



        return ok('Employees list', [
            'employees' =>  $data['query']->get(),
            'count'     =>  $data['count'],
        ]);

        //return ok('Permission granted');
    }

    /**
     * Store a newly created employee in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        /*
        $request->validate([
            'city'          =>  'required|string|min:3|max:100',
            'job'           =>  'required|string|min:3|max:100',
            'salary'        =>  'required|numeric',
            'joining_date'  =>  'required|date_format:Y-m-d|before_or_equal:' . now(),
            'user_id'       =>  'required|exists:users,id|unique:employees,user_id',
        ]);
    */

        $validator = Validator::make($request->all(), [
            'city'          =>  'required|string|min:3|max:100',
            'job'           =>  'required|string|min:3|max:100',
            'salary'        =>  'required|numeric',
            'joining_date'  =>  'required|date_format:Y-m-d|before_or_equal:' . now(),
            'user_id'       =>  'required|exists:users,id|unique:employees,user_id',
        ])->validate();

        $user = User::with('roles')->findOrFail($request->user_id);

        if ($user->roles[0]->name == "Employee") {
            //$employee = Employee::create($request->only(['city', 'job', 'salary', 'joining_date', 'user_id']));
            $employee = Employee::create($validator);
            return ok('Employee data added successfully');
        }
        return ok('select user are not employee!!!');

        //return ok('Permission granted');
    }

    /**
     * Update the specified employee in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'city'          =>  'required|string|min:3|max:100',
            'job'           =>  'required|string|min:3|max:100',
            'salary'        =>  'required|numeric',
            'joining_date'  =>  'required|date_format:Y-m-d|before_or_equal:' . now(),
            'user_id'       =>  'required|exists:users,id|unique:employees,user_id,' . $id . ',id',
        ]);

        $user = User::with('roles')->findOrFail($request->user_id);

        if ($user->roles[0]->name == "Employee") {
            $employee->update($request->only(['city', 'job', 'salary', 'joining_date', 'user_id']));

            return ok('Employee data updated successfully');
        }
        return ok('select user are not employee!!!');
        //return ok('Permission granted');
    }

    /**
     * Display the specified employee.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        $employee = Employee::with('user', 'tasks')->findOrFail($id);

        return ok('Employee data', $employee);
        //return ok('Permission granted');
    }

    /**
     * Remove the specified employee from database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return ok('Employee data deleted successfully');

        //return ok('Permission granted');
    }
}
