<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTask;
use App\Models\Task;
use App\Traits\ListingApiTrait;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use ListingApiTrait;
    /**
     * Display a listing of the task.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        
        $this->ListingValidation();

        $query = Task::query();
        $searchable_fileds = ['title' ,'description'];
        
        $data = $this->filterSearchPagination($query ,$searchable_fileds);

        return ok('Task list',[
            'tasks' =>  $data['query']->get(),
            'count' =>  $data['count'],
        ]);

        //return ok('Permission granted');
    }

    /**
     * Store a newly created task in data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(StoreTask $request)
    {
        // $request->validate([
        //     'title'         =>  'required|min:3|max:100',
        //     'description'   =>  'required|min:5|max:255',
        //     'employee_id'   =>  'required|exists:employees,id'
        // ]);

        $task = Task::create($request->validated());
        return ok('Task data added successfully');
        
        //$task = Task::create($request->only(['title' ,'description' ,'employee_id']));

      
        //return ok('Permission granted');
    }

    /**
     * Update the specified task in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $request->validate([
            'title'         =>  'required|min:3|max:100',
            'description'   =>  'required|min:5|max:255',
            'employee_id'   =>  'required|exists:employees,id'
        ]);

        $task->update($request->only(['title' ,'description' ,'employee_id']));

        return ok('Task data uppdated successfully');
        
        //return ok('Permission granted');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        $task = Task::with('employee','employee.user')->findOrFail($id);
        return ok('Task Data',$task);

        //return ok('Permission granted');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return ok('task deleted successfully');
        
        //return ok('Permission granted');
    }
}
