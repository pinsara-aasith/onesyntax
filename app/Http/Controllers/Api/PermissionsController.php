<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use DateTime;
use Error;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAllPermissions(Request $req)
    {
        $permissions = [];
        $permissions_col = Permission::all();
        foreach ($permissions_col as $permission) {
            $permissionobj = [
                "id" => $permission->id,
                "name" => $permission->name,
                "created_at" => (new DateTime($permission->created_at))->format('Y-m-d H:i'),
            ];

            array_push($permissions, $permissionobj);
        }
        $response = ["data" => $permissions];
        $response["status"] = "success";
        return response()->json($response, 200);
    }

    public function getPermission(Request $req, $id)
    {
        $permission = Permission::where("id", $id)->firstOrFail();

        $permissionobj = [
            "id" => $permission->id,
            "name" => $permission->name,
            "created_at" => (new DateTime($permission->created_at))->format('Y-m-d H:i'),
        ];

        $response = ["data" => $permissionobj];
        $response["status"] = "success";
        return response()->json($response, 200);
    }

    public function createPermission(Request $req)
    {
        $req->validate([
            'name' => ['required'],
        ]);
        $per = Permission::where("name", $req->name)->first();
        if ($per) {
            $response["status"] = "failed";
            $response["message"] = "permission already exists";
            return response()->json($response, 200);
        } else {
            Permission::create(['name' => $req->name]);
            $response["status"] = "success";
            return response()->json($response, 200);
        }
    }

    public function updatePermission(Request $req, $id)
    {
        $req->validate([
            'name' => ['required'],
        ]);
        $permission = Permission::where("id", $id)->firstOrFail();
        $per = Permission::where("name", $req->name)->first();
        if ($per) {
            $response["status"] = "failed";
            $response["message"] = "permission already exists";
            return response()->json($response, 200);
        } else {
            $permission->name = $req->name;
            $permission->save();
            $response["status"] = "success";
            return response()->json($response, 200);
        }
    }
}
