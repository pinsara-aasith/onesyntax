<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use DateTime;
use Error;
use Illuminate\Http\Request;

class StatesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAllStates(Request $req)
    {
        $states = [];
        $states_col = State::all();
        foreach ($states_col as $state) {
            $country = Country::where("id", $state->country)->first();

            $stateobj = [
                "id" => $state->id,
                "name" => $state->name,
                "country" =>  $country ?  $country->name : null,
            ];

            array_push($states, $stateobj);
        }
        $response = ["data" => $states];
        $response["status"] = "success";
        return response()->json($response, 200);
    }

    public function getAllStatesByCountry(Request $req, $id)
    {
        $states = [];
        $states_col = State::where('country',$id)->get();
        foreach ($states_col as $state) {
            $stateobj = [
                "id" => $state->id,
                "name" => $state->name,
                "country" => $state->country,
            ];

            array_push($states, $stateobj);
        }
        $response = ["data" => $states];
        $response["status"] = "success";
        return response()->json($response, 200);
    }

    public function getState(Request $req, $id)
    {
        $state = State::where("id", $id)->firstOrFail();
        $country = Country::where("id", $state->country)->first();

        $stateobj = [
            "id" => $state->id,
            "name" => $state->name,
            "country" =>  $country ?  $country->id : null,
        ];

        $response = ["data" => $stateobj];
        $response["status"] = "success";
        return response()->json($response, 200);
    }

    public function createState(Request $req)
    {
        $req->validate([
            'name' => ['required'],
            'country' => ['required']
        ]);
        $state = new State();
        $state->name = $req->input("name");
        $state->country = $req->input("country");
        $state->saveOrFail();
        $response["status"] = "success";
        return response()->json($response, 200);
    }

    public function updateState(Request $req, $id)
    {
        $req->validate([
            'name' => ['required'],
            'country' => ['required'],
        ]);
        $state = State::where("id", $id)->firstOrFail();

        $state->name = $req->input("name");
        $state->country = $req->input("country");
        $state->saveOrFail();
        $response["status"] = "success";
        return response()->json($response, 200);
    }

    public function deleteState(Request $req, $id)
    {
        $state =  State::where("id", $id)->firstOrFail();
        $state->delete();
        $response = ["status" => "success"];
        return response()->json($response, 200);
    }
}
