<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use DateTime;
use Error;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAllRoles(Request $req)
    {
        $roles = [];
        $roles_col = Role::all();
        foreach ($roles_col as $role) {
            $roleobj = [
                "id" => $role->id,
                "name" => $role->name,
                "created_at" => (new DateTime($role->created_at))->format('Y-m-d H:i'),
                "permissions" => $role->getAllPermissions()
            ];
            array_push($roles, $roleobj);
        }
        $response = ["data" => $roles];
        $response["status"] = "success";
        return response()->json($response, 200);
    }

    public function getRole(Request $req, $id)
    {
        $role = Role::where("id", $id)->firstOrFail();

        $roleobj = [
            "id" => $role->id,
            "name" => $role->name,
            "created_at" => (new DateTime($role->created_at))->format('Y-m-d H:i'), "permissions" => $role->getAllPermissions()
        ];

        $response = ["data" => $roleobj];
        $response["status"] = "success";
        return response()->json($response, 200);
    }

    public function createRole(Request $req)
    {
        $req->validate([
            'name' => ['required'],
        ]);
        $per = Role::where("name", $req->name)->first();
        if ($per) {
            $response["status"] = "failed";
            $response["message"] = "role already exists";
            return response()->json($response, 200);
        } else {
            $role = Role::create(['name' => $req->name]);
            if ($req->permissions) {
                foreach ($req->permissions as $permission) {
                    $role->givePermissionTo($permission["name"]);
                }
            }
            $response["status"] = "success";
            return response()->json($response, 200);
        }
    }

    public function updateRole(Request $req, $id)
    {
        $req->validate([
            'name' => ['required'],
        ]);
        $role = Role::where("id", $id)->firstOrFail();

        $role->name = $req->name;
        $hasPermissions = $role->permissions;
        foreach ($hasPermissions as $hasPermission) {
            $role->revokePermissionTo($hasPermission["name"]);
        }
        if ($req->permissions) {
            foreach ($req->permissions as $permission) {
                $role->givePermissionTo($permission["name"]);
            }
        }
        $response["status"] = "success";

        $role->save();
        $response["status"] = "success";
        return response()->json($response, 200);
    }
}
