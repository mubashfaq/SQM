<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Support\Facades\DB;
class PermissionController extends Controller
{
        public function index()
        {
            return view('Admin.Role.index');
        }

        public function permission($id)
        {
            $permissions = RolePermission::where('role_id', $id)->pluck('permission')->toArray();
            return view('Admin.Role.permission', compact('permissions'));
        }
        public function storepermission(Request $request)
        {
            $validated_data = $request->validate([
                'role_id' => 'required|integer',
                'permission' => 'nullable|array',
            ]);
            RolePermission::where('role_id', $validated_data['role_id'])->delete();
            if (isset($validated_data['permission'])) {
            $rolePermissions = [];
            foreach ($validated_data['permission'] as $permission) {
                $rolePermission = RolePermission::create([
                    'role_id' => $validated_data['role_id'],
                    'permission' => $permission,
                ]);
                $rolePermissions[] = $rolePermission;
            }
                
            }
            return redirect()->back()->with('success', 'Permissions updated successfully.');
        }


        public function create()
        {
            $roledatas = Role::all();
            return view('Admin.Role.create',compact('roledatas'));
        }

        public function edit($id)  
        {
            $role = Role::find($id);
            return response()->json($role);
        }

 
        public function store(Request $request)
        {
            $validated_data = $request->validate([
                'title' => 'required|string',
            ]);
            if (Role::where('title', $validated_data['title'])->exists()) {
                return redirect()->route('role.create')->with('error', 'Role already exists.');
            }
            $brand = Role::create($validated_data);    
            return redirect()->route('role.create')->with('success', 'Role created successfully.');
        }

        public function update(Request $request, $id)
        {
            $validatedData = $request->validate([
                'title' => 'required|string',
            ]);
            $role = Role::find($id); 
    
            if (!$role) {
                return Redirect::back()->withErrors(['Role not found.']); 
            }
            if (Role::where('title', $validatedData['title'])->where('id', '!=', $id)->exists()) {
                return redirect()->route('role.create', ['role' => $role])->with('error', 'Role already exists.');
            }
            $role->update($validatedData);
            return redirect()->route('role.create', ['role' => $role])->with('success', 'Role updated successfully.');
        }

        public function destroy($id)
        {
            $role = Role::find($id); 
            if (!$role) {
                return Redirect::back()->withErrors(['Role not found.']);
            }
            $role->delete();
            return redirect()->route('role.create')->with('success', 'Role deleted successfully.');
        }

        


        
}
