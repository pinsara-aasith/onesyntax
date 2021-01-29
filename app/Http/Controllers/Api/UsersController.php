<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    
    public function manualLogoutAndRedirect(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAllUsers(Request $req)
    {
        $users = [];
        $users_col = User::where('deleted_at', null)->get();
        foreach ($users_col as $user) {
            $role = Role::where('name', (sizeof($user->getRoleNames()) > 0 ? $user->getRoleNames()[0] : "N/A"))->first();
            $roleNo = -1;
            if ($role) {
                $roleNo = $role->id;
            }
            $userobj = [
                "id" => $user->id,
                "username" => $user->username,
                "firstname" => $user->firstname,
                "lastname" => $user->lastname,
                "email" => $user->email,
                "roleNo" => $roleNo,
                "role" => strtoupper(sizeof($user->getRoleNames()) > 0 ? $user->getRoleNames()[0] : "N/A")
            ];

            array_push($users, $userobj);
        }
        $response = ["data" => $users];
        $response["status"] = "success";
        return response()->json($response, 200);
    }

    public function getUser(Request $req, $id)
    {
        $user = User::where("id", $id)->firstOrFail();
        $userobj = [
            "id" => $user->id,
            "username" => $user->username,
            "firstname" => $user->firstname,
            "lastname" => $user->lastname,
            "email" => $user->email,
            "role" => sizeof($user->getRoleNames()) > 0 ? $user->getRoleNames()[0] : "N/A"
        ];

        $response = ["data" => $userobj];
        $response["status"] = "success";
        return response()->json($response, 200);
    }

    public function createUser(Request $req)
    {
        $req->validate([
            'username' => ['required'],
            'firstname' => ['required'],
            'lastname' => ['required'],
            'password' => ['required'],
            'role' => ['required'],
            'email' => ['required', 'email:filter', 'unique:users,email'],
        ]);
        $user = new User();
        $user->username = $req->input("username");
        $user->firstname = $req->input("firstname");
        $user->lastname = $req->input("lastname");
        $user->password = Hash::make($req->input('password'));
        $user->email = $req->input("email");
        $user->saveOrFail();
        $user->syncRoles([$req->input("role")]);
        $response["status"] = "success";
        return response()->json($response, 200);
    }

    public function deleteUser(Request $req, $id)
    {
        $user =  User::where("id", $id)->firstOrFail();
        $user->delete();
        $response = ["status" => "success"];
        return response()->json($response, 200);
    }

    public function updateUser(Request $req, $id)
    {
        $req->validate([
            'username' => ['required'],
            'firstname' => ['required'],
            'lastname' => ['required'],
            'password' => ['required'],
            'role' => ['required'],
            'email' => ['required', 'email:filter'],
        ]);
        
        $user = User::where("id", $id)->firstOrFail();
        $user->username = $req->input("username");
        $user->firstname = $req->input("firstname");
        $user->lastname = $req->input("lastname");
        $user->password = Hash::make($req->input('password'));
        $user->email = $req->input("email");
        $user->saveOrFail();
        $user->syncRoles([$req->input("role")]);
        $response["status"] = "success";
        return response()->json($response, 200);
    }
}
