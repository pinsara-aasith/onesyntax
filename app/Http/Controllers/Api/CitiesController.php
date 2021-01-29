<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use DateTime;
use Error;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAllCitiesByState(Request $req,$id)
    {
        $cities = [];
        $cities_col = City::where('state',$id)->get();
        foreach ($cities_col as $city) {
            $cityobj = [
                "id" => $city->id,
                "name" => $city->name,
                "state" =>  $city->state,
            ];

            array_push($cities, $cityobj);
        }
        $response = ["data" => $cities];
        $response["status"] = "success";
        return response()->json($response, 200);
    }

    public function getAllCities(Request $req)
    {
        $cities = [];
        $cities_col = City::all();
        foreach ($cities_col as $city) {
            $state = State::where("id", $city->state)->first();

            $cityobj = [
                "id" => $city->id,
                "name" => $city->name,
                "state" =>  $state ?  $state->name : null,
            ];

            array_push($cities, $cityobj);
        }
        $response = ["data" => $cities];
        $response["status"] = "success";
        return response()->json($response, 200);
    }

    public function getCity(Request $req, $id)
    {
        $city = City::where("id", $id)->firstOrFail();
        $state = State::where("id", $city->state)->first();

        $cityobj = [
            "id" => $city->id,
            "name" => $city->name,
            "state" =>  $state ?  $state->id : null,
        ];

        $response = ["data" => $cityobj];
        $response["status"] = "success";
        return response()->json($response, 200);
    }

    public function createCity(Request $req)
    {
        $req->validate([
            'name' => ['required'],
            'state' => ['required']
        ]);
        $city = new City();
        $city->name = $req->input("name");
        $city->state = $req->input("state");
        $city->saveOrFail();
        $response["status"] = "success";
        return response()->json($response, 200);
    }

    public function updateCity(Request $req, $id)
    {
        $req->validate([
            'name' => ['required'],
            'state' => ['required'],
        ]);
        $city = City::where("id", $id)->firstOrFail();

        $city->name = $req->input("name");
        $city->state = $req->input("state");
        $city->saveOrFail();
        $response["status"] = "success";
        return response()->json($response, 200);
    }

    public function deleteCity(Request $req, $id)
    {
        $city =  City::where("id", $id)->firstOrFail();
        $city->delete();
        $response = ["status" => "success"];
        return response()->json($response, 200);
    }
}
