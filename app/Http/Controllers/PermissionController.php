<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Traits\ListingApiTrait;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    use ListingApiTrait;

    /**
     * Display a listing of the permission.
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $this->ListingValidation();

        $query = Permission::query();
        $searchable_fields = ['name', 'description'];

        $data = $this->filterSearchPagination($query, $searchable_fields);

        return ok('permistion list', [
            'permissions'   =>  $data['query']->get(),
            'count'         =>  $data['count'],
        ]);
    }

    /**
     * Store a newly created permission in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'name'          =>  'required|string|min:8|max:150|unique:permissions,name',
            'description'   =>  'nullable|max:255',
            'add_access'    =>  'nullable|boolean',
            'delete_access' =>  'nullable|boolean',
            'view_access'   =>  'nullable|boolean',
            'update_access' =>  'nullable|boolean',
            'module_id'     =>  'required|array|exists:modules,id',
        ]);

        $permission = Permission::create($request->only(['name', 'description']));

        $permission->modules()->attach($request->module_id, $request->only(['add_access', 'delete_access', 'view_access', 'update_access']));

        return ok('Permission data added successfully');
    }

    /**
     * Update the specified permission in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $request->validate([
            'name'          =>  'required|string|min:8|max:150|unique:permissions,name,' . $id . ',id',
            'description'   =>  'nullable|max:255',
            'add_access'    =>  'nullable|boolean',
            'delete_access' =>  'nullable|boolean',
            'view_access'   =>  'nullable|boolean',
            'update_access' =>  'nullable|boolean',
            'module_id'     =>  'required|array|exists:modules,id',
        ]);

        $permission->update($request->only(['name', 'description']));

        $permission->modules()->updateExistingPivot($request->module_id, $request->only(['add_access', 'delete_access', 'view_access', 'update_access']));

        return ok('Permission data updated successfully');
    }

    /**
     * Display the specified permission.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        $permission = Permission::with('modules')->findOrFail($id);

        return ok('Permission data', $permission);
    }

    /**
     * Remove the specified permission from database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);

        $permission->delete();

        $permission->modules()->detach();

        return ok('Permission data deleted successfully');
    }
}
