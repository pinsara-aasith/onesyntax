<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use DateTime;
use Error;
use Illuminate\Http\Request;

class CountriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAllCountries(Request $req)
    {
        $countries = [];
        $countries_col = Country::all();
        foreach ($countries_col as $country) {
            $countryobj = [
                "id" => $country->id,
                "name" => $country->name,
                "country_code" => $country->country_code,
                "created_at" => (new DateTime($country->created_at))->format('Y-m-d H:i'),
            ];

            array_push($countries, $countryobj);
        }
        $response = ["data" => $countries];
        $response["status"] = "success";
        return response()->json($response, 200);
    }

    public function getCountry(Request $req, $id)
    {
        $country = Country::where("id", $id)->firstOrFail();

        $countryobj = [
            "id" => $country->id,
            "name" => $country->name,
            "country_code" =>  $country->country_code,
        ];

        $response = ["data" => $countryobj];
        $response["status"] = "success";
        return response()->json($response, 200);
    }

    public function createCountry(Request $req)
    {
        $req->validate([
            'name' => ['required'],
            'country_code' => ['required', 'max:3'],
        ]);
        $per = Country::where("name", $req->name)->first();
        if ($per) {
            $response["status"] = "failed";
            $response["message"] = "country already exists";
            return response()->json($response, 200);
        } else {
            $country = new Country();
            $country->name = $req->input("name");
            $country->country_code = $req->input("country_code");
            $country->saveOrFail();
            $response["status"] = "success";
            return response()->json($response, 200);
        }
    }

    public function updateCountry(Request $req, $id)
    {
        $req->validate([
            'name' => ['required'],
            'country_code' => ['required', 'max:3'],
        ]);
        $country = Country::where("id", $id)->firstOrFail();

        $country->name = $req->input("name");
        $country->country_code = $req->input("country_code");
        $country->saveOrFail();
        $response["status"] = "success";
        return response()->json($response, 200);
    }

    public function deleteCountry(Request $req, $id)
    {
        $country =  Country::where("id", $id)->firstOrFail();
        $country->delete();
        $response = ["status" => "success"];
        return response()->json($response, 200);
    }
}
