<?php

namespace App\Http\Controllers;

use App\Enums\Furtran\EventTypeEnum;
use App\Enums\Furtran\RgResponseEnum;
use App\Enums\Furtran\VehicleServiceTypeEnum;
use App\Enums\Furips1\EventNatureEnum;
use App\Enums\Furips1\ReferenceTypeEnum;
use App\Enums\Furips1\RgoResponseEnum;
use App\Enums\Furips1\SurgicalComplexityEnum;
use App\Enums\Furips1\TransportServiceTypeEnum;
use App\Enums\Furips1\VehicleTypeEnum;
use App\Enums\Furips1\VictimConditionEnum;
use App\Enums\Furips2\ServiceTypeEnum;
use App\Enums\GenderEnum;
use App\Enums\Invoice\StatusInvoiceEnum;
use App\Enums\YesNoEnum;
use App\Enums\ZoneEnum;
use App\Http\Resources\CatalogoCum\CatalogoCumSelectResource;
use App\Http\Resources\Cie10\Cie10SelectInfiniteResource;
use App\Http\Resources\CodeGlosa\CodeGlosaSelectInfiniteResource;
use App\Http\Resources\CodeGlosaAnswer\CodeGlosaAnswerSelectResource;
use App\Http\Resources\ConceptoRecaudo\ConceptoRecaudoSelectResource;
use App\Http\Resources\CondicionyDestinoUsuarioEgreso\CondicionyDestinoUsuarioEgresoSelectInfiniteResource;
use App\Http\Resources\Country\CountrySelectResource;
use App\Http\Resources\CupsRips\CupsRipsSelectInfiniteResource;
use App\Http\Resources\Dci\DciSelectResource;
use App\Http\Resources\Decreto780de2026\Decreto780de2026SelectResource;
use App\Http\Resources\Departamento\DepartamentoSelectResource;
use App\Http\Resources\Entity\EntitySelectResource;
use App\Http\Resources\Ffm\FfmSelectResource;
use App\Http\Resources\GrupoServicio\GrupoServicioSelectInfiniteResource;
use App\Http\Resources\InsuranceStatus\InsuranceStatusSelectResource;
use App\Http\Resources\IpsCodHabilitacion\IpsCodHabilitacionSelectInfiniteResource;
use App\Http\Resources\IpsNoReps\IpsNoRepsSelectInfiniteResource;
use App\Http\Resources\Ium\IumSelectResource;
use App\Http\Resources\ModalidadAtencion\ModalidadAtencionSelectInfiniteResource;
use App\Http\Resources\Municipio\MunicipioSelectResource;
use App\Http\Resources\Pais\PaisSelectResource;
use App\Http\Resources\Patient\PatientSelectResource;
use App\Http\Resources\RipsCausaExternaVersion2\RipsCausaExternaVersion2SelectInfiniteResource;
use App\Http\Resources\RipsFinalidadConsultaVersion2\RipsFinalidadConsultaVersion2SelectInfiniteResource;
use App\Http\Resources\RipsTipoDiagnosticoPrincipalVersion2\RipsTipoDiagnosticoPrincipalVersion2SelectInfiniteResource;
use App\Http\Resources\RipsTipoUsuarioVersion2\RipsTipoUsuarioVersion2SelectResource;
use App\Http\Resources\ServiceVendor\ServiceVendorSelectResource;
use App\Http\Resources\Servicio\ServicioSelectInfiniteResource;
use App\Http\Resources\Sexo\SexoSelectResource;
use App\Http\Resources\TipoIdPisis\TipoIdPisisSelectResource;
use App\Http\Resources\TipoMedicamentoPosVersion2\TipoMedicamentoPosVersion2SelectInfiniteResource;
use App\Http\Resources\TipoNota\TipoNotaSelectResource;
use App\Http\Resources\TipoOtrosServicios\TipoOtrosServiciosSelectResource;
use App\Http\Resources\TypeCodeGlosa\TypeCodeGlosaSelectResource;
use App\Http\Resources\TypeDocument\TypeDocumentSelectResource;
use App\Http\Resources\Umm\UmmSelectInfiniteResource;
use App\Http\Resources\Upr\UprSelectResource;
use App\Http\Resources\ViaIngresoUsuario\ViaIngresoUsuarioSelectInfiniteResource;
use App\Http\Resources\ZonaVersion2\ZonaVersion2SelectResource;
use App\Repositories\CatalogoCumRepository;
use App\Repositories\Cie10Repository;
use App\Repositories\CityRepository;
use App\Repositories\CodeGlosaAnswerRepository;
use App\Repositories\CodeGlosaRepository;
use App\Repositories\ConceptoRecaudoRepository;
use App\Repositories\CondicionyDestinoUsuarioEgresoRepository;
use App\Repositories\CountryRepository;
use App\Repositories\CupsRipsRepository;
use App\Repositories\DciRepository;
use App\Repositories\Decreto780de2026Repository;
use App\Repositories\DepartamentoRepository;
use App\Repositories\EntityRepository;
use App\Repositories\FfmRepository;
use App\Repositories\GrupoServicioRepository;
use App\Repositories\InsuranceStatusRepository;
use App\Repositories\IpsCodHabilitacionRepository;
use App\Repositories\IpsNoRepsRepository;
use App\Repositories\IumRepository;
use App\Repositories\ModalidadAtencionRepository;
use App\Repositories\MunicipioRepository;
use App\Repositories\PaisRepository;
use App\Repositories\PatientRepository;
use App\Repositories\RipsCausaExternaVersion2Repository;
use App\Repositories\RipsFinalidadConsultaVersion2Repository;
use App\Repositories\RipsTipoDiagnosticoPrincipalVersion2Repository;
use App\Repositories\RipsTipoUsuarioVersion2Repository;
use App\Repositories\ServiceVendorRepository;
use App\Repositories\ServicioRepository;
use App\Repositories\SexoRepository;
use App\Repositories\StateRepository;
use App\Repositories\TipoIdPisisRepository;
use App\Repositories\TipoMedicamentoPosVersion2Repository;
use App\Repositories\TipoNotaRepository;
use App\Repositories\TipoOtrosServiciosRepository;
use App\Repositories\TypeCodeGlosaRepository;
use App\Repositories\TypeDocumentRepository;
use App\Repositories\TypeEntityRepository;
use App\Repositories\TypeVendorRepository;
use App\Repositories\UmmRepository;
use App\Repositories\UprRepository;
use App\Repositories\UserRepository;
use App\Repositories\ViaIngresoUsuarioRepository;
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
        protected TipoOtrosServiciosRepository $tipoOtrosServiciosRepository,
        protected ConceptoRecaudoRepository $conceptoRecaudoRepository,
        protected ModalidadAtencionRepository $modalidadAtencionRepository,
        protected GrupoServicioRepository $grupoServicioRepository,
        protected ServicioRepository $servicioRepository,
        protected RipsFinalidadConsultaVersion2Repository $ripsFinalidadConsultaVersion2Repository,
        protected Cie10Repository $cie10Repository,
        protected RipsTipoDiagnosticoPrincipalVersion2Repository $ripsTipoDiagnosticoPrincipalVersion2Repository,
        protected RipsCausaExternaVersion2Repository $ripsCausaExternaVersion2Repository,
        protected ViaIngresoUsuarioRepository $viaIngresoUsuarioRepository,
        protected TipoMedicamentoPosVersion2Repository $tipoMedicamentoPosVersion2Repository,
        protected UmmRepository $ummRepository,
        protected CondicionyDestinoUsuarioEgresoRepository $condicionyDestinoUsuarioEgresoRepository,
        protected IpsNoRepsRepository $ipsNoRepsRepository,
        protected IpsCodHabilitacionRepository $ipsCodHabilitacionRepository,
        protected InsuranceStatusRepository $insuranceStatusRepository,
        protected TypeCodeGlosaRepository $typeCodeGlosaRepository,
        protected CatalogoCumRepository $catalogoCumRepository,
        protected UprRepository $uprRepository,
        protected FfmRepository $ffmRepository,
        protected DciRepository $dciRepository,
        protected IumRepository $iumRepository,
        protected DepartamentoRepository $departamentoRepository,
        protected Decreto780de2026Repository $decreto780de2026Repository,
        protected CodeGlosaAnswerRepository $codeGlosaAnswerRepository,
    ) {}

    public function selectInfiniteCountries(Request $request)
    {
        $countries = $this->countryRepository->list($request->all());
        $dataCountries = CountrySelectResource::collection($countries);

        return [
            'countries_arrayInfo' => $dataCountries,
            'countries_countLinks' => $countries->lastPage(),
        ];
    }

    public function selectStates($country_id)
    {
        $states = $this->stateRepository->selectList($country_id);

        return [
            'code' => 200,
            'states' => $states,
        ];
    }

    public function selectCities($state_id)
    {
        $cities = $this->cityRepository->selectList($state_id);

        return [
            'code' => 200,
            'cities' => $cities,
        ];
    }

    public function selectCitiesCountry($country_id)
    {
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
    }

    public function selectTypeEntity($request)
    {
        $typeEntities = $this->typeEntityRepository->find($request->all());

        return [
            'code' => 200,
            'typeEntities' => $typeEntities,
        ];
    }

    public function selectInfiniteEntities(Request $request)
    {

        $entities = $this->entityRepository->paginate($request->all());

        $dataCountries = EntitySelectResource::collection($entities);

        return [
            'entities_arrayInfo' => $dataCountries,
            'entities_countLinks' => $entities->lastPage(),
        ];
    }

    public function selectInfiniteServiceVendor(Request $request)
    {

        $serviceVendors = $this->serviceVendorRepository->paginate($request->all());

        $dataCountries = ServiceVendorSelectResource::collection($serviceVendors);

        return [
            'serviceVendors_arrayInfo' => $dataCountries,
            'serviceVendors_countLinks' => $serviceVendors->lastPage(),
        ];
    }

    public function selectInfiniteTypeDocument(Request $request)
    {

        $typeDocuments = $this->typeDocumentRepository->paginate($request->all());

        $dataCountries = TypeDocumentSelectResource::collection($typeDocuments);

        return [
            'typeDocuments_arrayInfo' => $dataCountries,
            'typeDocuments_countLinks' => $typeDocuments->lastPage(),
        ];
    }

    public function autoCompleteDataPatients(Request $request)
    {
        $data = $this->patientRepository->selectList($request->all(), fieldTitle: 'full_name', limit: 10);

        return [
            'code' => 200,
            'data' => $data,
        ];
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

    public function selectInfiniteTipoIdPisis(Request $request)
    {
        $tipoIdPisis = $this->tipoIdPisisRepository->paginate($request->all());
        $data = TipoIdPisisSelectResource::collection($tipoIdPisis);

        return [
            'code' => 200,
            'tipoIdPisis_arrayInfo' => $data,
            'tipoIdPisis_countLinks' => $tipoIdPisis->lastPage(),
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

    public function selectInfiniteDepartamento(Request $request)
    {
        $departamento = $this->departamentoRepository->paginate($request->all());
        $data = DepartamentoSelectResource::collection($departamento);

        return [
            'code' => 200,
            'departamentos_arrayInfo' => $data,
            'departamentos_countLinks' => $departamento->lastPage(),
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

    public function selectInfiniteTipoOtrosServicios(Request $request)
    {
        $tipoOtrosServicios = $this->tipoOtrosServiciosRepository->list($request->all());
        $data = TipoOtrosServiciosSelectResource::collection($tipoOtrosServicios);

        return [
            'code' => 200,
            'tipoOtrosServicios_arrayInfo' => $data,
            'tipoOtrosServicios_countLinks' => $tipoOtrosServicios->lastPage(),
        ];
    }

    public function selectInfiniteConceptoRecaudo(Request $request)
    {
        $conceptoRecaudo = $this->conceptoRecaudoRepository->list($request->all());
        $data = ConceptoRecaudoSelectResource::collection($conceptoRecaudo);

        return [
            'code' => 200,
            'conceptoRecaudo_arrayInfo' => $data,
            'conceptoRecaudo_countLinks' => $conceptoRecaudo->lastPage(),
        ];
    }

    public function selectStatusInvoiceEnum(Request $request)
    {
        // Obtener todos los casos del enum
        $status = StatusInvoiceEnum::cases();

        // Mapear los casos a un formato con value y title
        $status = collect($status)->map(function ($item) {
            return [
                'value' => $item,
                'title' => $item->description(),
            ];
        });

        // Filtrar por descripción si se envía un parámetro de búsqueda
        if ($request->has('searchQueryInfinite') && ! empty($request->input('searchQueryInfinite'))) {
            $searchTerm = strtolower($request->input('searchQueryInfinite'));
            $status = $status->filter(function ($item) use ($searchTerm) {
                return str_contains(strtolower($item['title']), $searchTerm);
            });
        }

        return [
            'code' => 200,
            'statusInvoiceEnum_arrayInfo' => $status->values()->toArray(), // Convertir a array y resetear índices
            'statusInvoiceEnum_countLinks' => 1,
        ];
    }

    public function selectInfiniteModalidadAtencion(Request $request)
    {
        $modalidadAtencion = $this->modalidadAtencionRepository->list($request->all());
        $dataModalidadAtencion = ModalidadAtencionSelectInfiniteResource::collection($modalidadAtencion);

        return [
            'code' => 200,
            'modalidadAtencion_arrayInfo' => $dataModalidadAtencion,
            'modalidadAtencion_countLinks' => $modalidadAtencion->lastPage(),
        ];
    }

    public function selectInfiniteGrupoServicio(Request $request)
    {
        $grupoServicio = $this->grupoServicioRepository->list($request->all());
        $dataGrupoServicio = GrupoServicioSelectInfiniteResource::collection($grupoServicio);

        return [
            'code' => 200,
            'grupoServicio_arrayInfo' => $dataGrupoServicio,
            'grupoServicio_countLinks' => $grupoServicio->lastPage(),
        ];
    }

    public function selectInfiniteServicio(Request $request)
    {
        $servicio = $this->servicioRepository->list($request->all());
        $dataServicio = ServicioSelectInfiniteResource::collection($servicio);

        return [
            'code' => 200,
            'servicio_arrayInfo' => $dataServicio,
            'servicio_countLinks' => $servicio->lastPage(),
        ];
    }

    public function selectInfiniteRipsFinalidadConsultaVersion2(Request $request)
    {
        $ripsFinalidadConsultaVersion2 = $this->ripsFinalidadConsultaVersion2Repository->list($request->all());
        $dataRipsFinalidadConsultaVersion2 = RipsFinalidadConsultaVersion2SelectInfiniteResource::collection($ripsFinalidadConsultaVersion2);

        return [
            'code' => 200,
            'ripsFinalidadConsultaVersion2_arrayInfo' => $dataRipsFinalidadConsultaVersion2,
            'ripsFinalidadConsultaVersion2_countLinks' => $ripsFinalidadConsultaVersion2->lastPage(),
        ];
    }

    public function selectInfiniteCie10(Request $request)
    {
        $cie10 = $this->cie10Repository->list($request->all());
        $dataCie10 = Cie10SelectInfiniteResource::collection($cie10);

        return [
            'code' => 200,
            'cie10_arrayInfo' => $dataCie10,
            'cie10_countLinks' => $cie10->lastPage(),
        ];
    }

    public function selectInfiniteRipsTipoDiagnosticoPrincipalVersion2(Request $request)
    {
        $ripsTipoDiagnosticoPrincipalVersion2 = $this->ripsTipoDiagnosticoPrincipalVersion2Repository->list($request->all());
        $dataRipsTipoDiagnosticoPrincipalVersion2 = RipsTipoDiagnosticoPrincipalVersion2SelectInfiniteResource::collection($ripsTipoDiagnosticoPrincipalVersion2);

        return [
            'code' => 200,
            'ripsTipoDiagnosticoPrincipalVersion2_arrayInfo' => $dataRipsTipoDiagnosticoPrincipalVersion2,
            'ripsTipoDiagnosticoPrincipalVersion2_countLinks' => $ripsTipoDiagnosticoPrincipalVersion2->lastPage(),
        ];
    }

    public function selectInfiniteRipsCausaExternaVersion2(Request $request)
    {
        $ripsCausaExternaVersion2 = $this->ripsCausaExternaVersion2Repository->list($request->all());
        $dataRipsCausaExternaVersion2 = RipsCausaExternaVersion2SelectInfiniteResource::collection($ripsCausaExternaVersion2);

        return [
            'code' => 200,
            'ripsCausaExternaVersion2_arrayInfo' => $dataRipsCausaExternaVersion2,
            'ripsCausaExternaVersion2_countLinks' => $ripsCausaExternaVersion2->lastPage(),
        ];
    }

    public function selectInfiniteViaIngresoUsuario(Request $request)
    {
        $viaIngresoUsuario = $this->viaIngresoUsuarioRepository->list($request->all());
        $dataViaIngresoUsuario = ViaIngresoUsuarioSelectInfiniteResource::collection($viaIngresoUsuario);

        return [
            'code' => 200,
            'viaIngresoUsuario_arrayInfo' => $dataViaIngresoUsuario,
            'viaIngresoUsuario_countLinks' => $viaIngresoUsuario->lastPage(),
        ];
    }

    public function selectInfiniteTipoMedicamentoPosVersion2(Request $request)
    {
        $tipoMedicamentoPosVersion2 = $this->tipoMedicamentoPosVersion2Repository->list($request->all());
        $dataTipoMedicamentoPosVersion2 = TipoMedicamentoPosVersion2SelectInfiniteResource::collection($tipoMedicamentoPosVersion2);

        return [
            'code' => 200,
            'tipoMedicamentoPosVersion2_arrayInfo' => $dataTipoMedicamentoPosVersion2,
            'tipoMedicamentoPosVersion2_countLinks' => $tipoMedicamentoPosVersion2->lastPage(),
        ];
    }

    public function selectInfiniteUmm(Request $request)
    {
        $umm = $this->ummRepository->list($request->all());
        $dataUmm = UmmSelectInfiniteResource::collection($umm);

        return [
            'code' => 200,
            'umm_arrayInfo' => $dataUmm,
            'umm_countLinks' => $umm->lastPage(),
        ];
    }

    public function selectInfiniteCondicionyDestinoUsuarioEgreso(Request $request)
    {
        $condicionyDestinoUsuarioEgreso = $this->condicionyDestinoUsuarioEgresoRepository->list($request->all());
        $dataCondicionyDestinoUsuarioEgreso = CondicionyDestinoUsuarioEgresoSelectInfiniteResource::collection($condicionyDestinoUsuarioEgreso);

        return [
            'code' => 200,
            'condicionyDestinoUsuarioEgreso_arrayInfo' => $dataCondicionyDestinoUsuarioEgreso,
            'condicionyDestinoUsuarioEgreso_countLinks' => $condicionyDestinoUsuarioEgreso->lastPage(),
        ];
    }

    public function selectInfiniteIpsNoReps(Request $request)
    {
        $ipsNoReps = $this->ipsNoRepsRepository->list($request->all());
        $dataIpsNoReps = IpsNoRepsSelectInfiniteResource::collection($ipsNoReps);

        return [
            'code' => 200,
            'ipsNoReps_arrayInfo' => $dataIpsNoReps,
            'ipsNoReps_countLinks' => $ipsNoReps->lastPage(),
        ];
    }

    public function selectInfiniteIpsCodHabilitacion(Request $request)
    {
        $ipsCodHabilitacion = $this->ipsCodHabilitacionRepository->list($request->all());
        $dataIpsCodHabilitacion = IpsCodHabilitacionSelectInfiniteResource::collection($ipsCodHabilitacion);

        return [
            'code' => 200,
            'ipsCodHabilitacion_arrayInfo' => $dataIpsCodHabilitacion,
            'ipsCodHabilitacion_countLinks' => $ipsCodHabilitacion->lastPage(),
        ];
    }

    public function selectInfiniteInsuranceStatus(Request $request)
    {

        $insuranceStatus = $this->insuranceStatusRepository->paginate($request->all());

        $dataInsuranceStatus = InsuranceStatusSelectResource::collection($insuranceStatus);

        return [
            'insuranceStatus_arrayInfo' => $dataInsuranceStatus,
            'insuranceStatus_countLinks' => $insuranceStatus->lastPage(),
        ];
    }

    public function selectInfiniteTypeCodeGlosa(Request $request)
    {

        $typeCodeGlosa = $this->typeCodeGlosaRepository->paginate($request->all());

        $dataTypeCodeGlosa = TypeCodeGlosaSelectResource::collection($typeCodeGlosa);

        return [
            'typeCodeGlosa_arrayInfo' => $dataTypeCodeGlosa,
            'typeCodeGlosa_countLinks' => $typeCodeGlosa->lastPage(),
        ];
    }

    public function selectInfiniteCatalogoCum(Request $request)
    {
        $catalogoCum = $this->catalogoCumRepository->list($request->all());

        $dataCatalogoCum = CatalogoCumSelectResource::collection($catalogoCum);

        return [
            'catalogoCum_arrayInfo' => $dataCatalogoCum,
            'catalogoCum_countLinks' => $catalogoCum->lastPage(),
        ];
    }

    public function selectInfiniteUpr(Request $request)
    {
        $upr = $this->uprRepository->list($request->all());

        $dataUpr = UprSelectResource::collection($upr);

        return [
            'upr_arrayInfo' => $dataUpr,
            'upr_countLinks' => $upr->lastPage(),
        ];
    }

    public function selectInfiniteFfm(Request $request)
    {
        $ffm = $this->ffmRepository->list($request->all());

        $dataFfm = FfmSelectResource::collection($ffm);

        return [
            'ffm_arrayInfo' => $dataFfm,
            'ffm_countLinks' => $ffm->lastPage(),
        ];
    }

    public function selectInfiniteDci(Request $request)
    {
        $dci = $this->dciRepository->list($request->all());

        $dataDci = DciSelectResource::collection($dci);

        return [
            'dci_arrayInfo' => $dataDci,
            'dci_countLinks' => $dci->lastPage(),
        ];
    }

    public function selectInfiniteIum(Request $request)
    {
        $ium = $this->iumRepository->list($request->all());

        $dataIum = IumSelectResource::collection($ium);

        return [
            'ium_arrayInfo' => $dataIum,
            'ium_countLinks' => $ium->lastPage(),
        ];
    }

    public function selectRgoResponseEnum(Request $request)
    {
        // Obtener todos los casos del enum
        $status = RgoResponseEnum::cases();

        // Mapear los casos a un formato con value y title
        $status = collect($status)->map(function ($item) {
            return [
                'value' => $item,
                'title' => $item->description(),
            ];
        });

        // Filtrar por descripción si se envía un parámetro de búsqueda
        if ($request->has('searchQueryInfinite') && ! empty($request->input('searchQueryInfinite'))) {
            $searchTerm = strtolower($request->input('searchQueryInfinite'));
            $status = $status->filter(function ($item) use ($searchTerm) {
                return str_contains(strtolower($item['title']), $searchTerm);
            });
        }

        return [
            'code' => 200,
            'rgoResponseEnum_arrayInfo' => $status->values()->toArray(), // Convertir a array y resetear índices
            'rgoResponseEnum_countLinks' => 1,
        ];
    }

    public function selectVictimConditionEnum(Request $request)
    {
        // Obtener todos los casos del enum
        $status = VictimConditionEnum::cases();

        // Mapear los casos a un formato con value y title
        $status = collect($status)->map(function ($item) {
            return [
                'value' => $item,
                'title' => $item->description(),
            ];
        });

        // Filtrar por descripción si se envía un parámetro de búsqueda
        if ($request->has('searchQueryInfinite') && ! empty($request->input('searchQueryInfinite'))) {
            $searchTerm = strtolower($request->input('searchQueryInfinite'));
            $status = $status->filter(function ($item) use ($searchTerm) {
                return str_contains(strtolower($item['title']), $searchTerm);
            });
        }

        return [
            'code' => 200,
            'victimConditionEnum_arrayInfo' => $status->values()->toArray(), // Convertir a array y resetear índices
            'victimConditionEnum_countLinks' => 1,
        ];
    }

    public function selectEventNatureEnum(Request $request)
    {
        // Obtener todos los casos del enum
        $status = EventNatureEnum::cases();

        // Mapear los casos a un formato con value y title
        $status = collect($status)->map(function ($item) {
            return [
                'value' => $item,
                'title' => $item->description(),
            ];
        });

        // Filtrar por descripción si se envía un parámetro de búsqueda
        if ($request->has('searchQueryInfinite') && ! empty($request->input('searchQueryInfinite'))) {
            $searchTerm = strtolower($request->input('searchQueryInfinite'));
            $status = $status->filter(function ($item) use ($searchTerm) {
                return str_contains(strtolower($item['title']), $searchTerm);
            });
        }

        return [
            'code' => 200,
            'eventNatureEnum_arrayInfo' => $status->values()->toArray(), // Convertir a array y resetear índices
            'eventNatureEnum_countLinks' => 1,
        ];
    }

    public function selectZoneEnum(Request $request)
    {
        // Obtener todos los casos del enum
        $status = ZoneEnum::cases();

        // Mapear los casos a un formato con value y title
        $status = collect($status)->map(function ($item) {
            return [
                'value' => $item,
                'title' => $item->description(),
            ];
        });

        // Filtrar por descripción si se envía un parámetro de búsqueda
        if ($request->has('searchQueryInfinite') && ! empty($request->input('searchQueryInfinite'))) {
            $searchTerm = strtolower($request->input('searchQueryInfinite'));
            $status = $status->filter(function ($item) use ($searchTerm) {
                return str_contains(strtolower($item['title']), $searchTerm);
            });
        }

        return [
            'code' => 200,
            'zoneEnum_arrayInfo' => $status->values()->toArray(), // Convertir a array y resetear índices
            'ZoneEnum_countLinks' => 1,
        ];
    }

    public function selectReferenceTypeEnum(Request $request)
    {
        // Obtener todos los casos del enum
        $status = ReferenceTypeEnum::cases();

        // Mapear los casos a un formato con value y title
        $status = collect($status)->map(function ($item) {
            return [
                'value' => $item,
                'title' => $item->description(),
            ];
        });

        // Filtrar por descripción si se envía un parámetro de búsqueda
        if ($request->has('searchQueryInfinite') && ! empty($request->input('searchQueryInfinite'))) {
            $searchTerm = strtolower($request->input('searchQueryInfinite'));
            $status = $status->filter(function ($item) use ($searchTerm) {
                return str_contains(strtolower($item['title']), $searchTerm);
            });
        }

        return [
            'code' => 200,
            'referenceTypeEnum_arrayInfo' => $status->values()->toArray(), // Convertir a array y resetear índices
            'referenceTypeEnum_countLinks' => 1,
        ];
    }

    public function selectVehicleTypeEnum(Request $request)
    {
        // Obtener todos los casos del enum
        $status = VehicleTypeEnum::cases();

        // Mapear los casos a un formato con value y title
        $status = collect($status)->map(function ($item) {
            return [
                'value' => $item,
                'title' => $item->description(),
            ];
        });

        // Filtrar por descripción si se envía un parámetro de búsqueda
        if ($request->has('searchQueryInfinite') && ! empty($request->input('searchQueryInfinite'))) {
            $searchTerm = strtolower($request->input('searchQueryInfinite'));
            $status = $status->filter(function ($item) use ($searchTerm) {
                return str_contains(strtolower($item['title']), $searchTerm);
            });
        }

        return [
            'code' => 200,
            'vehicleTypeEnum_arrayInfo' => $status->values()->toArray(), // Convertir a array y resetear índices
            'vehicleTypeEnum_countLinks' => 1,
        ];
    }

    public function selectYesNoEnum(Request $request)
    {
        // Obtener todos los casos del enum
        $status = YesNoEnum::cases();

        // Mapear los casos a un formato con value y title
        $status = collect($status)->map(function ($item) {
            return [
                'value' => $item,
                'title' => $item->description(),
            ];
        });

        // Filtrar por descripción si se envía un parámetro de búsqueda
        if ($request->has('searchQueryInfinite') && ! empty($request->input('searchQueryInfinite'))) {
            $searchTerm = strtolower($request->input('searchQueryInfinite'));
            $status = $status->filter(function ($item) use ($searchTerm) {
                return str_contains(strtolower($item['title']), $searchTerm);
            });
        }

        return [
            'code' => 200,
            'yesNoEnum_arrayInfo' => $status->values()->toArray(), // Convertir a array y resetear índices
            'yesNoEnum_countLinks' => 1,
        ];
    }

    public function selectSurgicalComplexityEnum(Request $request)
    {
        // Obtener todos los casos del enum
        $status = SurgicalComplexityEnum::cases();

        // Mapear los casos a un formato con value y title
        $status = collect($status)->map(function ($item) {
            return [
                'value' => $item,
                'title' => $item->description(),
            ];
        });

        // Filtrar por descripción si se envía un parámetro de búsqueda
        if ($request->has('searchQueryInfinite') && ! empty($request->input('searchQueryInfinite'))) {
            $searchTerm = strtolower($request->input('searchQueryInfinite'));
            $status = $status->filter(function ($item) use ($searchTerm) {
                return str_contains(strtolower($item['title']), $searchTerm);
            });
        }

        return [
            'code' => 200,
            'surgicalComplexityEnum_arrayInfo' => $status->values()->toArray(), // Convertir a array y resetear índices
            'surgicalComplexityEnum_countLinks' => 1,
        ];
    }

    public function selectTransportServiceTypeEnum(Request $request)
    {
        // Obtener todos los casos del enum
        $status = TransportServiceTypeEnum::cases();

        // Mapear los casos a un formato con value y title
        $status = collect($status)->map(function ($item) {
            return [
                'value' => $item,
                'title' => $item->description(),
            ];
        });

        // Filtrar por descripción si se envía un parámetro de búsqueda
        if ($request->has('searchQueryInfinite') && ! empty($request->input('searchQueryInfinite'))) {
            $searchTerm = strtolower($request->input('searchQueryInfinite'));
            $status = $status->filter(function ($item) use ($searchTerm) {
                return str_contains(strtolower($item['title']), $searchTerm);
            });
        }

        return [
            'code' => 200,
            'transportServiceTypeEnum_arrayInfo' => $status->values()->toArray(), // Convertir a array y resetear índices
            'transportServiceTypeEnum_countLinks' => 1,
        ];
    }

    public function selectServiceTypeEnum(Request $request)
    {
        // Obtener todos los casos del enum
        $status = ServiceTypeEnum::cases();

        // Mapear los casos a un formato con value y title
        $status = collect($status)->map(function ($item) {
            return [
                'value' => $item,
                'title' => $item->description(),
            ];
        });

        // Filtrar por descripción si se envía un parámetro de búsqueda
        if ($request->has('searchQueryInfinite') && ! empty($request->input('searchQueryInfinite'))) {
            $searchTerm = strtolower($request->input('searchQueryInfinite'));
            $status = $status->filter(function ($item) use ($searchTerm) {
                return str_contains(strtolower($item['title']), $searchTerm);
            });
        }

        return [
            'code' => 200,
            'serviceTypeEnum_arrayInfo' => $status->values()->toArray(), // Convertir a array y resetear índices
            'serviceTypeEnum_countLinks' => 1,
        ];
    }

    public function selectInfiniteDecreto780de2026(Request $request)
    {
        $decreto780de2026 = $this->decreto780de2026Repository->list($request->all());

        $dataDecreto780de2026 = Decreto780de2026SelectResource::collection($decreto780de2026);

        return [
            'decreto780de2026_arrayInfo' => $dataDecreto780de2026,
            'decreto780de2026_countLinks' => $decreto780de2026->lastPage(),
        ];
    }
    
    public function selectRgResponseEnum(Request $request)
    {
        // Obtener todos los casos del enum
        $status = RgResponseEnum::cases();
        
        // Mapear los casos a un formato con value y title
        $status = collect($status)->map(function ($item) {
            return [
                'value' => $item,
                'title' => $item->description(),
            ];
        });

        // Filtrar por descripción si se envía un parámetro de búsqueda
        if ($request->has('searchQueryInfinite') && ! empty($request->input('searchQueryInfinite'))) {
            $searchTerm = strtolower($request->input('searchQueryInfinite'));
            $status = $status->filter(function ($item) use ($searchTerm) {
                return str_contains(strtolower($item['title']), $searchTerm);
            });
        }
        
        return [
            'code' => 200,
            'rgResponseEnum_arrayInfo' => $status->values()->toArray(), // Convertir a array y resetear índices
            'rgResponseEnum_countLinks' => 1,
        ];
    }

    public function selectVehicleServiceTypeEnum(Request $request)
    {
        // Obtener todos los casos del enum
        $status = VehicleServiceTypeEnum::cases();

        // Mapear los casos a un formato con value y title
        $status = collect($status)->map(function ($item) {
            return [
                'value' => $item,
                'title' => $item->description(),
            ];
        });
        
        // Filtrar por descripción si se envía un parámetro de búsqueda
        if ($request->has('searchQueryInfinite') && ! empty($request->input('searchQueryInfinite'))) {
            $searchTerm = strtolower($request->input('searchQueryInfinite'));
            $status = $status->filter(function ($item) use ($searchTerm) {
                return str_contains(strtolower($item['title']), $searchTerm);
            });
        }
        
        return [
            'code' => 200,
            'vehicleServiceTypeEnum_arrayInfo' => $status->values()->toArray(), // Convertir a array y resetear índices
            'vehicleServiceTypeEnum_countLinks' => 1,
        ];
    }
    
    public function selectGenderEnum(Request $request)
    {
        // Obtener todos los casos del enum
        $status = GenderEnum::cases();

        // Mapear los casos a un formato con value y title
        $status = collect($status)->map(function ($item) {
            return [
                'value' => $item,
                'title' => $item->description(),
            ];
        });

        // Filtrar por descripción si se envía un parámetro de búsqueda
        if ($request->has('searchQueryInfinite') && ! empty($request->input('searchQueryInfinite'))) {
            $searchTerm = strtolower($request->input('searchQueryInfinite'));
            $status = $status->filter(function ($item) use ($searchTerm) {
                return str_contains(strtolower($item['title']), $searchTerm);
            });
        }

        return [
            'code' => 200,
            'genderEnum_arrayInfo' => $status->values()->toArray(), // Convertir a array y resetear índices
            'genderEnum_countLinks' => 1,
        ];
    }
    
    public function selectEventTypeEnum(Request $request)
    {
        // Obtener todos los casos del enum
        $status = EventTypeEnum::cases();
        
        // Mapear los casos a un formato con value y title
        $status = collect($status)->map(function ($item) {
            return [
                'value' => $item,
                'title' => $item->description(),
            ];
        });
        
        // Filtrar por descripción si se envía un parámetro de búsqueda
        if ($request->has('searchQueryInfinite') && ! empty($request->input('searchQueryInfinite'))) {
            $searchTerm = strtolower($request->input('searchQueryInfinite'));
            $status = $status->filter(function ($item) use ($searchTerm) {
                return str_contains(strtolower($item['title']), $searchTerm);
            });
        }
        
        return [
            'code' => 200,
            'eventTypeEnum_arrayInfo' => $status->values()->toArray(), // Convertir a array y resetear índices
            'eventTypeEnum_countLinks' => 1,
        ];
    }
    
        public function selectCodeGlosaAnswer(Request $request)
        {
            $codeGlosaAnswer = $this->codeGlosaAnswerRepository->list($request->all());
    
            $codeGlosaAnswers = CodeGlosaAnswerSelectResource::collection($codeGlosaAnswer);
    
            return [
                'codeGlosaAnswer_arrayInfo' => $codeGlosaAnswers,
                'codeGlosaAnswer_countLinks' => $codeGlosaAnswer->lastPage(),
            ];
        }
}
