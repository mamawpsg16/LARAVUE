<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_roles = DB::table('roles as r')
        ->JOIN('role_user as ru','r.id','ru.role_id')
        ->JOIN('users as u','ru.user_id','u.id')
        ->select('r.id','r.name', DB::raw('GROUP_CONCAT(DISTINCT u.email SEPARATOR  ", ") as users'))
        ->groupBy('r.id','r.name')
        ->orderByDesc('r.created_at')
        ->get();
        return response()->json($user_roles);
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
            $validator = Validator::make($request->only(['role','user']), [
                'role' => ['required',   function ($attribute, $value, $fail) {
                    // dd($attribute,$value);
                    $existingCount = DB::table('role_user')
                        ->where('role_id', $value)
                        ->count();
                    
                    if ($existingCount > 0) {
                        $fail('The user already has this role.');
                    }
                }],
                'user' => ['required','array','min:1']
            ]);
            
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            
            // Perform your database operations here
            // foreach($request->input('module_permissions') as $key => $module_permission){
                foreach($request->input('user') as $user_id){
                    DB::table('role_user')
                     ->insert([
                         'role_id' => $request->input('role'),
                         'user_id' => $user_id,
                         
                     ]);
                }
            // }
            DB::commit(); // Commit the transaction if all operations succeed
            
            // Return a success response if needed
            return response()->json(['role_id' => $request->input('role')]);
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
    public function show(Role $user_role)
    {
        $userRole = DB::table('roles as r')
        ->JOIN('role_user as ru','r.id','ru.role_id')
        ->JOIN('users as u','ru.user_id','u.id')
        ->select('r.id','r.name', DB::raw('GROUP_CONCAT(DISTINCT u.email SEPARATOR  ", ") as users'))
        ->where('r.id', $user_role->id)
        ->groupBy('r.id','r.name')
        ->first();
        return response()->json($userRole);
    }

    public function getUserRoleEditDetails(Role $role){
        
        $user_roles = DB::table('roles as r')
        ->JOIN('role_user as ru','r.id','ru.role_id')
        ->JOIN('users as u','ru.user_id','u.id')
        ->select('u.id','u.email')
        ->where('ru.role_id', $role->id)
        ->orderByDesc('r.created_at')
        ->get()->toArray();
        return response()->json(['user_roles_' => $user_roles,'role_' => $role]);
      
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Role $user_role)
    {

        try {
            DB::beginTransaction();
            $validator = Validator::make($request->only(['role','user']), [
                'role' => ['required',Rule::unique('roles', 'id')->ignore($user_role->id),],
                'user' => ['required','array','min:1']
            ]);
            
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            DB::table('role_user')
                     ->where('role_id',$user_role->id)
                     ->delete();
            
            // Perform your database operations here
            // foreach($request->input('module_permissions') as $key => $module_permission){
                foreach($request->input('user') as $user_id){
                    DB::table('role_user')
                     ->insert([
                         'role_id' => $request->input('role'),
                         'user_id' => $user_id,
                         
                     ]);
                }
            // }
            DB::commit(); // Commit the transaction if all operations succeed
            
            // Return a success response if needed
            return response()->json(['role_id' => $request->input('role')]);
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
        //
    }
}
