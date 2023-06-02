<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SideBarController extends Controller
{
    public function index(){

        // $modules = auth()->user()->roles()->with('modulePermissions')->get()
        // ->pluck('modulePermissions')
        // ->flatten()
        // ->unique('id')
        // ->map(function ($permission) {
        //     return [
        //         'id' => $permission->id,
        //         'name' => $permission->name,
        //         'route' => $permission->route,
        //         'icon' => $permission->icon,
        //         'description' => $permission->description,
        //     ];
        // })
        // ->values()
        // ->all();
        $modules = Module::all();
        
        $user_role_ids = auth()->user()->roles()->pluck('roles.id');

        $module_permissions = DB::table('role_module_permission')
        ->whereIn('role_id', $user_role_ids)
        ->select('module_id', DB::raw('GROUP_CONCAT(permission_id) as permissions'))
        ->groupBy('module_id')
        ->get();
        
        return response()->json(['modules' => $modules ?? [], 'permissions' => $module_permissions]);
    }
}
