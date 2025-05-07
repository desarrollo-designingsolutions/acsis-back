<?php

namespace App\Http\Controllers;

use App\Http\Resources\CodeGlosa\CodeGlosaSelectInfiniteResource;
use App\Http\Resources\Country\CountrySelectResource;
use App\Http\Resources\CupsRips\CupsRipsSelectInfiniteResource;
use App\Http\Resources\Entity\EntitySelectResource;
use App\Http\Resources\Municipio\MunicipioSelectResource;
use App\Http\Resources\Pais\PaisSelectResource;
use App\Http\Resources\Patient\PatientSelectResource;
use App\Http\Resources\RipsTipoUsuarioVersion2\RipsTipoUsuarioVersion2SelectResource;
use App\Http\Resources\ServiceVendor\ServiceVendorSelectResource;
use App\Http\Resources\Sexo\SexoSelectResource;
use App\Http\Resources\TipoIdPisis\TipoIdPisisSelectResource;
use App\Http\Resources\TipoNota\TipoNotaSelectResource;
use App\Http\Resources\TypeDocument\TypeDocumentSelectResource;
use App\Http\Resources\ZonaVersion2\ZonaVersion2SelectResource;
use App\Repositories\CityRepository;
use App\Repositories\CodeGlosaRepository;
use App\Repositories\CountryRepository;
use App\Repositories\CupsRipsRepository;
use App\Repositories\EntityRepository;
use App\Repositories\MunicipioRepository;
use App\Repositories\PaisRepository;
use App\Repositories\PatientRepository;
use App\Repositories\RipsTipoUsuarioVersion2Repository;
use App\Repositories\ServiceVendorRepository;
use App\Repositories\SexoRepository;
use App\Repositories\StateRepository;
use App\Repositories\TipoIdPisisRepository;
use App\Repositories\TipoNotaRepository;
use App\Repositories\TypeDocumentRepository;
use App\Repositories\TypeEntityRepository;
use App\Repositories\TypeVendorRepository;
use App\Repositories\UserRepository;
use App\Repositories\ZonaVersion2Repository;
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
        protected TipoNotaRepository $tipoNotaRepository,
        protected TipoIdPisisRepository $tipoIdPisisRepository,
        protected RipsTipoUsuarioVersion2Repository $ripsTipoUsuarioVersion2Repository,
        protected SexoRepository $sexoRepository,
        protected PaisRepository $paisRepository,
        protected MunicipioRepository $municipioRepository,
        protected ZonaVersion2Repository $zonaVersion2Repository,
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

    public function selectInfinitePatients(Request $request)
    {
        $patients = $this->patientRepository->paginate($request->all());
        $data = PatientSelectResource::collection($patients);

        return [
            'code' => 200,
            'patients_arrayInfo' => $data,
            'patients_countLinks' => $patients->lastPage(),
        ];
    }

    public function selectInfinitetipoNota(Request $request)
    {
        $tipoNota = $this->tipoNotaRepository->paginate($request->all());
        $data = TipoNotaSelectResource::collection($tipoNota);

        return [
            'code' => 200,
            'tipoNotas_arrayInfo' => $data,
            'tipoNotas_countLinks' => $tipoNota->lastPage(),
        ];
    }

    public function selectInfiniteTipoDocumento(Request $request)
    {
        $tipoIdPisis = $this->tipoIdPisisRepository->paginate($request->all());
        $data = TipoIdPisisSelectResource::collection($tipoIdPisis);

        return [
            'code' => 200,
            'tipoIdPisiss_arrayInfo' => $data,
            'tipoIdPisiss_countLinks' => $tipoIdPisis->lastPage(),
        ];
    }

    public function selectInfiniteTipoUsuario(Request $request)
    {
        $ripsTipoUsuarioVersion2 = $this->ripsTipoUsuarioVersion2Repository->paginate($request->all());
        $data = RipsTipoUsuarioVersion2SelectResource::collection($ripsTipoUsuarioVersion2);

        return [
            'code' => 200,
            'ripsTipoUsuarioVersion2s_arrayInfo' => $data,
            'ripsTipoUsuarioVersion2s_countLinks' => $ripsTipoUsuarioVersion2->lastPage(),
        ];
    }

    public function selectInfiniteSexo(Request $request)
    {
        $sexo = $this->sexoRepository->paginate($request->all());
        $data = SexoSelectResource::collection($sexo);

        return [
            'code' => 200,
            'sexos_arrayInfo' => $data,
            'sexos_countLinks' => $sexo->lastPage(),
        ];
    }

    public function selectInfinitePais(Request $request)
    {
        $pais = $this->paisRepository->paginate($request->all());
        $data = PaisSelectResource::collection($pais);

        return [
            'code' => 200,
            'paises_arrayInfo' => $data,
            'paises_countLinks' => $pais->lastPage(),
        ];
    }

    public function selectInfiniteMunicipio(Request $request)
    {
        $municipio = $this->municipioRepository->paginate($request->all());
        $data = MunicipioSelectResource::collection($municipio);

        return [
            'code' => 200,
            'municipios_arrayInfo' => $data,
            'municipios_countLinks' => $municipio->lastPage(),
        ];
    }

    public function selectInfiniteZonaVersion2(Request $request)
    {
        $zonaVersion2 = $this->zonaVersion2Repository->paginate($request->all());
        $data = ZonaVersion2SelectResource::collection($zonaVersion2);

        return [
            'code' => 200,
            'zonaVersion2s_arrayInfo' => $data,
            'zonaVersion2s_countLinks' => $zonaVersion2->lastPage(),
        ];
    }
}
