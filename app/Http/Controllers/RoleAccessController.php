<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Module;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoleAccessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   

    public function index()
    {
        // $modules = Module::all();
        // $permissions = Permission::all();
        $role_modules = DB::table('roles as r')
        ->JOIN('role_module_permission as rmp','rmp.role_id','r.id')
        ->JOIN('modules as m','rmp.module_id','m.id')
        ->select('r.id','r.name', DB::raw('GROUP_CONCAT(DISTINCT m.name SEPARATOR  ", ") as modules'))
        ->groupBy('r.name','r.id')
        ->orderByDesc('r.created_at')
        ->get();
        return response()->json([$role_modules]);
    }

    public function getRoleAccessDetails(){
        $modules = Module::all();
        $permissions = Permission::all();
        $roles = Role::all();
    
        return response()->json(['modules_' => $modules, 'permissions_' => $permissions, 'roles_' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->only(['name','module_permissions']), [
                'name' => ['required','unique:roles'],
                'module_permissions' => ['required']
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $role = Role::create(['name' => $request->input('name')]);
            // Perform your database operations here
            foreach($request->input('module_permissions') as $key => $module_permission){
                foreach($module_permission['selected_permission_ids'] as $permission_id){
                    DB::table('role_module_permission')
                     ->insert([
                         'role_id' => $role->id,
                         'module_id' => $module_permission['module_id'],
                         'permission_id' => $permission_id,
                     ]);
                }
            }
            DB::commit(); // Commit the transaction if all operations succeed
            
            // Return a success response if needed
            return response()->json(true);
        } catch (\Exception $e) {
            DB::rollBack(); // Roll back the transaction if an exception occurs
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::where('id',$id)->first();
        // dd($role);
        // DB::table('role_module_permission')
        // ->select('')
        // ->where('role_id',$role->id)
        // ->get();
        $module_permissions = DB::table('roles as r')
        ->join('role_module_permission as rmp', 'rmp.role_id', 'r.id')
        ->join('modules as m', 'rmp.module_id', 'm.id')
        ->join('permissions as p', 'rmp.permission_id', 'p.id')
        ->select('m.name', DB::raw('GROUP_CONCAT(DISTINCT p.name SEPARATOR ", ") as permissions'))
        ->where('role_id', $role->id)
        ->groupBy('m.name','m.id')
        ->orderBy('m.id') // Add the orderBy method here
        ->get();
    
        return response()->json(['module_permissions' => $module_permissions, 'role' => $role]);
    }

    public function getRoleAccessEditDetails(Role $role){
    $modules = Module::all();
    $permissions = Permission::all();

    $module_permissions = DB::table('role_module_permission as rmp')
    ->select('module_id','p.id as id','p.name')
    ->join('permissions as p', 'rmp.permission_id', 'p.id')
    ->where('role_id', $role->id)
    ->get()->toArray();
    

    return response()->json(['modules_' => $modules, 'permissions_' => $permissions, 'selected_module_permissions_' => $module_permissions,'selected_role' => $role]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Role $role_access)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->only(['name','module_permissions']), [
                'name' => ['required', Rule::unique('roles')->ignore($role_access->id)],
                'module_permissions' => ['required'],
                
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $role_access->update(['name' => $request->input('name')]);
            DB::table('role_module_permission')->where('role_id', $role_access->id)->delete();
            // Perform your database operations here
            foreach($request->input('module_permissions') as $key => $module_permission){
                foreach($module_permission['selected_permission_ids'] as $permission){
                    // dd($module_permission,$permission['id'],$role_access->id,$module_permission['module_id']);
                    DB::table('role_module_permission')
                     ->insert([
                         'role_id' => $role_access->id,
                         'module_id' => $module_permission['module_id'],
                         'permission_id' => $permission['id'],
                     ]);
                }
            }
            DB::commit(); // Commit the transaction if all operations succeed
            
            // Return a success response if needed
            return response()->json(true);
        } catch (\Exception $e) {
            DB::rollBack(); // Roll back the transaction if an exception occurs
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $role = Role::where('id',$id)->first();
            DB::table('role_module_permission')
            ->where('role_id',$role->id)
            ->delete();
            $role->delete();
            
            DB::commit();
            return response()->json(true);
        }catch (\Exception $e){
            DB::rollBack();
        }

    }
}
