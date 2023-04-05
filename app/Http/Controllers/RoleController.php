<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Traits\ListingApiTrait;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    use ListingApiTrait;

    /**
     * Display a listing of the role.
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $this->ListingValidation();

        $searchable_fields = ['name', 'description'];
        $query = Role::query();

        $data = $this->filterSearchPagination($query, $searchable_fields);

        return ok('Role list', [
            'roles'     =>  $data['query']->get(),
            'count'     =>  $data['count'],
        ]);
    }

    /**
     * Store a newly created role in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'name'          =>  'required|string|min:2|max:100|unique:roles,name',
            'description'   =>  'nullable|max:250|',
            'permission_id' =>  'required|array|exists:permissions,id',
        ]);

        $role = Role::create($request->only(['name', 'description']));

        $role->permissions()->attach($request->permission_id);

        return ok('Role added successfully');
    }

    /**
     * Update the specified role in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'          =>  'required|string|min:2|max:100|unique:roles,name,' . $id . ',id',
            'description'   =>  'nullable|max:250|',
            'permission_id' =>  'required|array|exists:permissions,id',
        ]);

        $role = Role::findOrFail($id);

        $role->update($request->only(['name', 'description']));

        $role->permissions()->sync($request->permission_id);

        return ok('Role data updated successfully');
    }

    /**
     * Display the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        $role = Role::with('users', 'permissions')->findOrFail($id);

        return ok('Role data', $role);
    }

    /**
     * Remove the specified role from database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return ok('Role data deleted successfully');
    }
}
