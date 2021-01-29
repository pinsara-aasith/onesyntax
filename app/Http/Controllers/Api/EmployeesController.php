<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAllEmployees(Request $req){
       
        $employees_col = Employee::all();
        return $this->getCommonEmployees($employees_col);
    }
    public function getAllEmployeesBySearchName(Request $req){
       
        $employees_col = Employee::where('firstname',"LIKE","%".$req->input('search')."%")
        ->orWhere('lastname',"LIKE","%".$req->input('search')."%")
        ->orWhere('middlename',"LIKE","%".$req->input('search')."%")->get();
        return $this->getCommonEmployees($employees_col);
    }
    public function getAllEmployeesByDepartment(Request $req,$id){
        $employees_col = Employee::where('department_id',$id)->get();
        return $this->getCommonEmployees($employees_col);
    }
    public function getCommonEmployees($employees_col)
    {
        $employees = [];
        foreach ($employees_col as $employee) {
            
            $employeeobj = [
                "id" => $employee->id,
                "lastname" => $employee->lastname,
                "firstname" => $employee->firstname,
                "middlename" => $employee->middlename,
                "address" => $employee->address,
                "department" => $employee->department_id ? $employee->department->name : "N/A",
                "city" =>   $employee->city_id ? $employee->city->name : "N/A",
                "country" =>   $employee->country_id ? $employee->country->name : "N/A",
                "state" => $employee->state_id ? $employee->state->name : "N/A",
                "zip" =>  $employee->zip,
                "birthdate" =>  $employee->birthdate,
                "datehired" =>  $employee->date_hired,
            ];

            array_push($employees, $employeeobj);
        }
        $response = ["data" => $employees];
        $response["status"] = "success";
        return response()->json($response, 200);
    }

    public function getEmployee(Request $req, $id)
    {
        $employee = Employee::where("id", $id)->firstOrFail();

        $employeeobj = [

            "lastname"=>$employee->lastname,
            "firstname"=>$employee->firstname,
            "middlename"=>$employee->middlename,
            "department"=>$employee->department_id,
            "city"=>$employee->city_id,
            "country"=>$employee->country_id,
            "zip"=>$employee->zip,
            "state"=>$employee->state_id,
            "address"=>$employee->address,
            "birthdate"=>$employee->birthdate,
            "datehired"=>$employee->date_hired,
        ];

        $response = ["data" => $employeeobj];
        $response["status"] = "success";
        return response()->json($response, 200);
    }

    public function createEmployee(Request $req)
    {
        $req->validate([
            "lastname" => ['required'],
            "firstname" => ['required'],
            "address" => ['required'],
            "zip" => ['required'],
            "department" => ['required'],
            "city" => ['required'],
            "state" => ['required'],
            "country" => ['required']
        ]);
        $employee = new Employee();
        $employee->lastname = $req->input("lastname");
        $employee->firstname = $req->input("firstname");
        $employee->middlename = $req->input("middlename");
        $employee->department_id = $req->input("department");
        $employee->city_id = $req->input("city");
        $employee->country_id = $req->input("country");
        $employee->zip = $req->input("zip");
        $employee->state_id = $req->input("state");
        $employee->address = $req->input("address");
        $employee->birthdate = $req->input("birthdate");
        $employee->date_hired = $req->input("datehired");
        $employee->saveOrFail();
        $response["status"] = "success";
        return response()->json($response, 200);
    }

    public function updateEmployee(Request $req, $id)
    {
        $req->validate([
            "lastname" => ['required'],
            "firstname" => ['required'],
            "address" => ['required'],
            "zip" => ['required'],
            "department" => ['required'],
            "city" => ['required'],
            "state" => ['required'],
            "country" => ['required']
        ]);
        $employee = Employee::where('id',$id)->firstOrFail();
        $employee->lastname = $req->input("lastname");
        $employee->firstname = $req->input("firstname");
        $employee->middlename = $req->input("middlename");
        $employee->department_id = $req->input("department");
        $employee->city_id = $req->input("city");
        $employee->country_id = $req->input("country");
        $employee->zip = $req->input("zip");
        $employee->state_id = $req->input("state");
        $employee->address = $req->input("address");
        $employee->birthdate = $req->input("birthdate");
        $employee->date_hired = $req->input("datehired");
        $employee->saveOrFail();
        $response["status"] = "success";
        return response()->json($response, 200);
    }



    public function deleteEmployee(Request $req, $id)
    {
        $employee =  Employee::where("id", $id)->firstOrFail();
        $employee->delete();
        $response = ["status" => "success"];
        return response()->json($response, 200);
    }
}
