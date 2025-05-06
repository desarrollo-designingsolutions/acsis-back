<?php

namespace App\Http\Controllers;

use App\Http\Resources\CodeGlosa\CodeGlosaSelectInfiniteResource;
use App\Http\Resources\Country\CountrySelectResource;
use App\Http\Resources\CupsRips\CupsRipsSelectInfiniteResource;
use App\Http\Resources\Entity\EntitySelectResource;
use App\Http\Resources\ServiceVendor\ServiceVendorSelectResource;
use App\Http\Resources\TypeDocument\TypeDocumentSelectResource;
use App\Http\Resources\TypeVendor\TypeVendorSelectInfiniteResource;
use App\Repositories\CityRepository;
use App\Repositories\CodeGlosaRepository;
use App\Repositories\CountryRepository;
use App\Repositories\CupsRipsRepository;
use App\Repositories\EntityRepository;
use App\Repositories\p;
use App\Repositories\PatientRepository;
use App\Repositories\ServiceVendorRepository;
use App\Repositories\StateRepository;
use App\Repositories\TypeDocumentRepository;
use App\Repositories\TypeEntityRepository;
use App\Repositories\TypeVendorRepository;
use App\Repositories\UserRepository;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;

class QueryController extends Controller
{
    use HttpResponseTrait;

    public function __construct(
        protected CountryRepository $countryRepository,
        protected TypeEntityRepository $typeEntityRepository,
        protected StateRepository $stateRepository,
        protected CityRepository $cityRepository,
        protected UserRepository $userRepository,
        protected TypeVendorRepository $typeVendorRepository,
        protected EntityRepository $entityRepository,
        protected ServiceVendorRepository $serviceVendorRepository,
        protected TypeDocumentRepository $typeDocumentRepository,
        protected PatientRepository $patientRepository,
        protected CodeGlosaRepository $codeGlosaRepository,
        protected CupsRipsRepository $cupsRipsRepository,
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

    public function selectTypeEntity($request)
    {
        return $this->execute(function () use ($request) {
            $typeEntities = $this->typeEntityRepository->find($request->all());

            return [
                'code' => 200,
                'typeEntities' => $typeEntities,
            ];
        });
    }

    public function selectInfiniteEntities(Request $request)
    {
        return $this->execute(function () use ($request) {

            $entities = $this->entityRepository->paginate($request->all());

            $dataCountries = EntitySelectResource::collection($entities);

            return [
                'entities_arrayInfo' => $dataCountries,
                'entities_countLinks' => $entities->lastPage(),
            ];
        });
    }

    public function selectInfiniteServiceVendor(Request $request)
    {
        return $this->execute(function () use ($request) {

            $serviceVendors = $this->serviceVendorRepository->paginate($request->all());

            $dataCountries = ServiceVendorSelectResource::collection($serviceVendors);

            return [
                'serviceVendors_arrayInfo' => $dataCountries,
                'serviceVendors_countLinks' => $serviceVendors->lastPage(),
            ];
        });
    }

    public function selectInfiniteTypeDocument(Request $request)
    {
        return $this->execute(function () use ($request) {

            $typeDocuments = $this->typeDocumentRepository->paginate($request->all());

            $dataCountries = TypeDocumentSelectResource::collection($typeDocuments);

            return [
                'typeDocuments_arrayInfo' => $dataCountries,
                'typeDocuments_countLinks' => $typeDocuments->lastPage(),
            ];
        });
    }

    public function autoCompleteDataPatients(Request $request)
    {
        return $this->execute(function () use ($request) {
            $data = $this->patientRepository->selectList($request->all(), fieldTitle: "full_name", limit: 10);

            return [
                'code' => 200,
                'data' => $data,
            ];
        });
    }
    public function selectInfiniteCodeGlosa(Request $request)
    {
        $request['is_active'] = 1;
        $codeGlosa = $this->codeGlosaRepository->list($request->all());
        $dataCodeGlosa = CodeGlosaSelectInfiniteResource::collection($codeGlosa);

        return [
            'code' => 200,
            'codeGlosa_arrayInfo' => $dataCodeGlosa,
            'codeGlosa_countLinks' => $codeGlosa->lastPage(),
        ];
    }

    public function selectInfiniteCupsRips(Request $request)
    {
        $cupsRips = $this->cupsRipsRepository->list($request->all());
        $dataCupsRips = CupsRipsSelectInfiniteResource::collection($cupsRips);

        return [
            'code' => 200,
            'cupsRips_arrayInfo' => $dataCupsRips,
            'cupsRips_countLinks' => $cupsRips->lastPage(),
        ];
    }
}
