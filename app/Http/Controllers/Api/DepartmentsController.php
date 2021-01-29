<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use DateTime;
use Error;
use Illuminate\Http\Request;

class DepartmentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAllDepartments(Request $req)
    {
        $departments = [];
        $departments_col = Department::all();
        foreach ($departments_col as $department) {
            $departmentobj = [
                "id" => $department->id,
                "name" => $department->name,
                "created_at" => (new DateTime($department->created_at))->format('Y-m-d H:i'),
            ];

            array_push($departments, $departmentobj);
        }
        $response = ["data" => $departments];
        $response["status"] = "success";
        return response()->json($response, 200);
    }

    public function getDepartment(Request $req, $id)
    {
        $department = Department::where("id", $id)->firstOrFail();

        $departmentobj = [
            "id" => $department->id,
            "name" => $department->name,
            "created_at" => (new DateTime($department->created_at))->format('Y-m-d H:i'),
        ];

        $response = ["data" => $departmentobj];
        $response["status"] = "success";
        return response()->json($response, 200);
    }

    public function createDepartment(Request $req)
    {
        $req->validate([
            'name' => ['required'],
        ]);
        $per = Department::where("name", $req->name)->first();
        if ($per) {
            $response["status"] = "failed";
            $response["message"] = "department already exists";
            return response()->json($response, 200);
        } else {
            $department = new Department();
            $department->name = $req->name;
            $department->saveOrFail();
            $response["status"] = "success";
            return response()->json($response, 200);
        }
    }

    public function updateDepartment(Request $req, $id)
    {
        $req->validate([
            'name' => ['required'],
        ]);
        $department = Department::where("id", $id)->firstOrFail();
        $per = Department::where("name", $req->name)->first();
        if ($per) {
            $response["status"] = "failed";
            $response["message"] = "department already exists";
            return response()->json($response, 200);
        } else {
            $department->name = $req->name;
            $department->saveOrFail();
            $response["status"] = "success";
            return response()->json($response, 200);
        }
    }

    public function deleteDepartment(Request $req, $id)
    {
        $department =  Department::where("id", $id)->firstOrFail();
        $department->delete();
        $response = ["status" => "success"];
        return response()->json($response, 200);
    }
}
