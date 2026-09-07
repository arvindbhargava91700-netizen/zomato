<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GeoController extends Controller
{
    /**
     * Return states for a given country (dependent dropdown).
     */
    public function states(Request $request): JsonResponse
    {
        $countryId = $request->get('country_id');

        if (!$countryId) {
            return response()->json([]);
        }

        $states = State::where('country_id', $countryId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($states);
    }

    /**
     * Return cities for a given state (dependent dropdown).
     */
    public function cities(Request $request): JsonResponse
    {
        $stateId = $request->get('state_id');

        if (!$stateId) {
            return response()->json([]);
        }

        $cities = City::where('state_id', $stateId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($cities);
    }

    /**
     * Resolve country/state/city names (from reverse geocoding) to IDs in our DB.
     * Returns errors for any that do not exist so the form can warn the user.
     */
    public function resolve(Request $request): JsonResponse
    {
        $countryName = trim((string) $request->get('country', ''));
        $stateName = trim((string) $request->get('state', ''));
        $cityName = trim((string) $request->get('city', ''));

        $errors = [];
        $data = [
            'country_id' => null,
            'state_id' => null,
            'city_id' => null,
        ];

        if ($countryName !== '') {
            $country = Country::where('name', $countryName)
                ->orWhere('name', 'like', $countryName . '%')
                ->orWhere('iso_code_2', strtoupper($countryName))
                ->orWhere('iso_code_3', strtoupper($countryName))
                ->first();

            if ($country) {
                $data['country_id'] = $country->id;
            } else {
                $errors['country_id'] = "Country \"{$countryName}\" is not available in our system.";
            }
        }

        if ($countryName !== '' && $stateName !== '' && $data['country_id']) {
            $state = State::where('country_id', $data['country_id'])
                ->where(function ($q) use ($stateName) {
                    $q->where('name', $stateName)
                        ->orWhere('name', 'like', $stateName . '%')
                        ->orWhere('state_code', strtoupper($stateName));
                })
                ->first();

            if ($state) {
                $data['state_id'] = $state->id;
            } else {
                $errors['state_id'] = "State \"{$stateName}\" is not available for the selected country.";
            }
        } elseif ($stateName !== '' && !$data['country_id']) {
            $errors['state_id'] = 'Country must be resolved before matching the state.';
        }

        if ($stateName !== '' && $cityName !== '' && $data['state_id']) {
            $city = City::where('state_id', $data['state_id'])
                ->where(function ($q) use ($cityName) {
                    $q->where('name', $cityName)
                        ->orWhere('name', 'like', $cityName . '%');
                })
                ->first();

            if ($city) {
                $data['city_id'] = $city->id;
            } else {
                $errors['city_id'] = "City \"{$cityName}\" is not available for the selected state.";
            }
        } elseif ($cityName !== '' && !$data['state_id']) {
            $errors['city_id'] = 'State must be resolved before matching the city.';
        }

        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'errors' => $errors,
                'data' => $data,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
