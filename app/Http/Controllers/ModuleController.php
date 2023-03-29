<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Traits\ListingApiTrait;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    use ListingApiTrait;

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

    public function create(Request $request)
    {
        $request->validate([
            'name'          =>  'required|string|min:5|max:150|unique:modules,name',
            'description'   =>  'nullable|max:255',
        ]);

        $module = Module::create($request->only(['name' ,'description']));

        return ok('Module data added successfully');
    }

    public function update(Request $request ,$id)
    {
        $request->validate([
            'name'          =>  'required|string|min:8|max:150|unique:modules,name,'.$id.',id',
            'description'   =>  'nullable|max:255',
        ]);

        $module  = Module::findOrFail($id);

        $module->update($request->only(['name' ,'description']));

        return ok('Module data updated successfully');
    }

    public function get($id)
    {
        $module  = Module::findOrFail($id);

        return ok('Module data',$module);
    }

    public function destroy($id)
    {
        $module  = Module::findOrFail($id);
        $module->delete();

        return ok('Module data deleted');
    }
}
