<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = Role::all();
        //return response()->json($role,500);
        return view('admin.admin_role', compact('role'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        $role = new Role;
        $role->role_name = $req->input('role_name');
        $role->level = $req->input('level');
        $role->status = $req->input('status');
        $role->save();
        $response = [
            'status' => 'success',
            'data' => $role
        ];
    
        return response()->json($response, 200);
    }

    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        return response()->json($role);
    }

    public function validateRoleForm(Request $request)
    {
        $roleName = $request->input('role_name');
        $exists = Role::where('role_name', $roleName)->exists();

        if ($exists) {
            return response()->json(['message' => 'Role name already exists'], 400);
        }

        return response()->json(['message' => 'Role name is available'], 200);
    }

    public function edit(Request $req, Role $role)
    {
        $role->role_name = strtoupper($req->input('role_name'));
        $role->level = $req->input('level');
        $role->status = $req->input('status');
        $role->save();
        return response()->json(['status' => 'success'], 200);
    }

    public function getRole($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        return response()->json($role);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();
    
        return response()->json(['status' => 'success'], 200);
    }
}
