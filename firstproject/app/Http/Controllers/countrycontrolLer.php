<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\createCountryRequest;
use App\Http\Resources\CountryRecource;

class countrycontrolLer extends Controller
{
    //



    
    public function allcountries()
    {

        $countries = Cache::remember('countries_list', 3600, function () {
            return Country::all();
        });


        return response()->json([
            'success' => true,
            'countries' => CountryRecource::collection($countries),
            'message' => 'Countries retrieved successfully',
        ], 200);

    }


    public function createCountry(createCountryRequest $request)
    {

        
        
        $country = new Country();
        $country->name = $request->name;
        $country->save();



        Cache::forget('countries_list');

        return response()->json([
            'success' => true,
            'message' => 'Country created successfully , cached data cleared',
            'country' => new CountryRecource($country),
        ], 200);
    }




}
