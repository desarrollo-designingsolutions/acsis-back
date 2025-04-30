<?php

namespace App\Http\Controllers;

use App\Http\Resources\Country\CountrySelectResource;
use App\Http\Resources\TypeVendor\TypeVendorSelectInfiniteResource;
use App\Repositories\CityRepository;
use App\Repositories\CountryRepository;
use App\Repositories\StateRepository;
use App\Repositories\TypeVendorRepository;
use App\Repositories\UserRepository;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;

class QueryController extends Controller
{
    use HttpResponseTrait;

    public function __construct(
        protected CountryRepository $countryRepository,
        protected StateRepository $stateRepository,
        protected CityRepository $cityRepository,
        protected UserRepository $userRepository,
        protected TypeVendorRepository $typeVendorRepository,
    ) {}

    public function selectInfiniteCountries(Request $request)
    {
        return $this->execute(function () use ($request) {
            $countries = $this->countryRepository->list($request->all());

            $dataCountries = CountrySelectResource::collection($countries);

            return [
                'countries_arrayInfo' => $dataCountries,
                'countries_countLinks' => $countries->lastPage(),
            ];
        });
    }

    public function selectStates($country_id)
    {
        return $this->execute(function () use ($country_id) {
            $states = $this->stateRepository->selectList($country_id);

            return [
                'code' => 200,
                'states' => $states,
            ];
        });
    }

    public function selectCities($state_id)
    {
        return $this->execute(function () use ($state_id) {
            $cities = $this->cityRepository->selectList($state_id);

            return [
                'code' => 200,
                'cities' => $cities,
            ];
        });
    }

    public function selectCitiesCountry($country_id)
    {
        return $this->execute(function () use ($country_id) {
            $country = $this->countryRepository->find($country_id, ['cities']);

            return [
                'code' => 200,
                'message' => 'Datos Encontrados',
                'cities' => $country['cities']->map(function ($item) {
                    return [
                        'value' => $item->id,
                        'title' => $item->name,
                    ];
                }),
            ];
        });
    }

}
