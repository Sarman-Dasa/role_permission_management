<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Traits\ListingApiTrait;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    use ListingApiTrait;

     /**
     * Display a listing of the module.
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $this->ListingValidation();

        $query = Module::query();
        $searchable_fields = ['name' ,'description'];

        $data = $this->filterSearchPagination($query ,$searchable_fields);

        return ok('Module list',[
            'modules'   =>  $data['query']->get(),
            'count'     =>  $data['count'],
        ]);
    }

    /**
     * Store a newly created module in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'name'          =>  'required|string|min:2|max:150|unique:modules,name',
            'description'   =>  'nullable|max:255',
        ]);

        $module = Module::create($request->only(['name' ,'description']));

        return ok('Module data added successfully');
    }

    /**
     * Update the specified module in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request ,$id)
    {
        $request->validate([
            'name'          =>  'required|string|min:2|max:150|unique:modules,name,'.$id.',id',
            'description'   =>  'nullable|max:255',
        ]);

        $module  = Module::findOrFail($id);

        $module->update($request->only(['name' ,'description']));

        return ok('Module data updated successfully');
    }

     /**
     * Display the specified module.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        $module  = Module::with('permissions','permissions.roles','permissions.modules')->findOrFail($id);

        return ok('Module data',$module);
    }

    /**
     * Remove the specified module from database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $module  = Module::findOrFail($id);
        $module->delete();

        return ok('Module data deleted');
    }
}
