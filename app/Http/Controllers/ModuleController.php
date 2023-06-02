<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        return response()->json($modules);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->only(['name','route','icon','description']), [
            'name' => 'required|unique:modules',
            'route' => 'required',
            'icon' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $module = Module::create([
            'name' => $request->input('name'),
            'route' => $request->input('route'),
            'icon' => $request->input('icon'),
            'description' => $request->input('description'),
        ]);

        return response()->json($module);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Module $module)
    {
        return response()->json($module);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Module $module)
    {
        // dd($request->all());
        $validator = Validator::make($request->only(['name','route','icon','description']), [
            'name' => ['required',Rule::unique('roles')->ignore($module->id)],
            'route' => 'required',
            'icon' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $module->update([
            'name' => $request->input('name'),
            'route' => $request->input('route'),
            'icon' => $request->input('icon'),
            'description' => $request->input('description'),
        ]);

        return response()->json($module);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Module $module)
    {
        $module->delete();
        return response()->json($module);
    }
}
