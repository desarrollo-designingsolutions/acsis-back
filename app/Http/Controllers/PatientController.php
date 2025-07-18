<?php

namespace App\Http\Controllers;

use App\Events\ModalError;
use App\Events\ProgressCircular;
use App\Exports\Patient\PatientExcelErrorsValidationExport;
use App\Exports\Patient\PatientExcelErrorsValidationInfoExport;
use App\Exports\Patient\PatientFormatExcelExport;
use App\Exports\Patient\PatientInformationExcelExport;
use App\Exports\PatientExcelExport;
use App\Helpers\Common\ErrorCollector;
use App\Helpers\Common\ImportXlsxValidator;
use App\Helpers\Constants;
use App\Http\Requests\Patient\PatientStoreRequest;
use App\Http\Requests\Patient\PatientUploadXlsxRequest;
use App\Http\Resources\Patient\PatientFormResource;
use App\Http\Resources\Patient\PatientListResource;
use App\Imports\Patient\PatientMasiveImport;
use App\Jobs\BrevoProcessSendEmail;
use App\Notifications\BellNotification;
use App\Repositories\MunicipioRepository;
use App\Repositories\PaisRepository;
use App\Repositories\PatientRepository;
use App\Repositories\RipsTipoUsuarioVersion2Repository;
use App\Repositories\SexoRepository;
use App\Repositories\TipoIdPisisRepository;
use App\Repositories\TypeEntityRepository;
use App\Repositories\UserRepository;
use App\Repositories\ZonaVersion2Repository;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class PatientController extends Controller
{
    use HttpResponseTrait;

    public function __construct(
        protected PatientRepository $patientRepository,
        protected UserRepository $userRepository,
        protected TypeEntityRepository $typeEntityRepository,
        protected QueryController $queryController,
        protected TipoIdPisisRepository $tipoIdPisisRepository,
        protected RipsTipoUsuarioVersion2Repository $ripsTipoUsuarioVersion2Repository,
        protected SexoRepository $sexoRepository,
        protected PaisRepository $paisRepository,
        protected MunicipioRepository $municipioRepository,
        protected ZonaVersion2Repository $zonaVersion2Repository,
    ) {}

    public function paginate(Request $request)
    {
        return $this->execute(function () use ($request) {
            $data = $this->patientRepository->paginate($request->all());
            $tableData = PatientListResource::collection($data);

            return [
                'code' => 200,
                'tableData' => $tableData,
                'lastPage' => $data->lastPage(),
                'totalData' => $data->total(),
                'totalPage' => $data->perPage(),
                'currentPage' => $data->currentPage(),
            ];
        });
    }

    public function create()
    {
        return $this->execute(function () {

            $tipoDocumento = $this->queryController->selectInfiniteTipoIdPisis(request());

            $tipoUsuario = $this->queryController->selectInfiniteTipoUsuario(request());
            $sexo = $this->queryController->selectInfiniteSexo(request());
            $pais = $this->queryController->selectInfinitePais(request());
            $municipio = $this->queryController->selectInfiniteMunicipio(request());
            $zonaVersion2 = $this->queryController->selectInfiniteZonaVersion2(request());

            return [
                'code' => 200,
                ...$tipoDocumento,
                ...$tipoUsuario,
                ...$sexo,
                ...$pais,
                ...$municipio,
                ...$zonaVersion2,
            ];
        });
    }

    public function store(PatientStoreRequest $request)
    {
        return $this->runTransaction(function () use ($request) {

            $patient = $this->patientRepository->store($request->all(), null);

            $patient->name = $patient->full_name;

            return [
                'code' => 200,
                'message' => 'Paciente agregado correctamente',
                'data' => $patient,
            ];
        });
    }

    public function edit($id)
    {
        return $this->execute(function () use ($id) {
            $patient = $this->patientRepository->find($id);
            $form = new PatientFormResource($patient);

            $tipoDocumento = $this->queryController->selectInfiniteTipoIdPisis(request());
            $tipoUsuario = $this->queryController->selectInfiniteTipoUsuario(request());
            $sexo = $this->queryController->selectInfiniteSexo(request());
            $pais = $this->queryController->selectInfinitePais(request());
            $municipio = $this->queryController->selectInfiniteMunicipio(request());
            $zonaVersion2 = $this->queryController->selectInfiniteZonaVersion2(request());

            return [
                'code' => 200,
                'form' => $form,
                ...$tipoDocumento,
                ...$tipoUsuario,
                ...$sexo,
                ...$pais,
                ...$municipio,
                ...$zonaVersion2,
            ];
        });
    }

    public function update(PatientStoreRequest $request, $id)
    {
        return $this->runTransaction(function () use ($request, $id) {

            $patient = $this->patientRepository->store($request->all(), $id);

            return [
                'code' => 200,
                'message' => 'Paciente modificado correctamente',
            ];
        });
    }

    public function delete($id)
    {
        return $this->runTransaction(function () use ($id) {
            $patient = $this->patientRepository->find($id);
            if ($patient) {

                // Verificar si hay registros relacionados
                if ($patient->invoices()->exists()) {
                    throw new \Exception(json_encode([
                        'message' => 'No se puede eliminar la paciente, porque tiene relación de datos en otros módulos',
                    ]));
                }

                $patient->delete();
                $msg = 'Registro eliminado correctamente';
            } else {
                $msg = 'El registro no existe';
            }

            return [
                'code' => 200,
                'message' => $msg,
            ];
        }, 200);
    }

    public function excelExport(Request $request)
    {
        return $this->execute(function () use ($request) {

            $request['typeData'] = 'all';

            $patient = $this->patientRepository->paginate($request->all());
             $patient->load(["tipo_id_pisi", "rips_tipo_usuario_version2", "sexo", "pais_residency", "municipio_residency", "zona_version2", "pais_origin"]);

            $excel = Excel::raw(new PatientExcelExport($patient), \Maatwebsite\Excel\Excel::XLSX);

            $excelBase64 = base64_encode($excel);

            return [
                'code' => 200,
                'excel' => $excelBase64,
            ];
        });
    }
    
    public function exportDataToPatientImportExcel(Request $request)
    {
        return $this->execute(function () use ($request) {

            ini_set('memory_limit', '1024M');

            $tipoIdPisis = $this->tipoIdPisisRepository->paginate([
                "typeData" => "all",
            ]);

            $ripsTipoUsuarioVersion2 = $this->ripsTipoUsuarioVersion2Repository->paginate([
                "typeData" => "all",
            ]);

            $sexos = $this->sexoRepository->paginate([
                "typeData" => "all",
            ]);

            $paises = $this->paisRepository->paginate([
                "typeData" => "all",
            ]);

            $municipios = $this->municipioRepository->paginate([
                "typeData" => "all",
            ]);

            $zonaVersion2 = $this->zonaVersion2Repository->paginate([
                "typeData" => "all",
            ]);

            $incapacity = [
                ['name' => 'SÍ'],
                ['name' => 'NO'],
            ];

            $excel = Excel::raw(new PatientInformationExcelExport($tipoIdPisis, $ripsTipoUsuarioVersion2, $sexos, $paises, $municipios, $zonaVersion2, $incapacity, $request->all()), \Maatwebsite\Excel\Excel::XLSX);

            $excelBase64 = base64_encode($excel);

            return [
                'code' => 200,
                'excel' => $excelBase64,
            ];
        });
    }
    
    public function exportFormatPatientImportExcel(Request $request)
    {
        return $this->execute(function () use ($request) {

            $excel = Excel::raw(new PatientFormatExcelExport([]), \Maatwebsite\Excel\Excel::XLSX);

            $excelBase64 = base64_encode($excel);

            return [
                'code' => 200,
                'excel' => $excelBase64,
            ];
        });
    }
    
    public function uploadXlsxPatient(PatientUploadXlsxRequest $request)
    {
        return $this->runTransaction(function () use ($request) {

            $keyErrorRedis = 'paginate:patient_import_errors_' . $request->input('user_id');

            $user_id = $request->input('user_id');
            $company_id = $request->input('company_id');

            $tipoIdPisis = $this->tipoIdPisisRepository->paginate([
                "typeData" => "all",
            ]);

            $ripsTipoUsuarioVersion2 = $this->ripsTipoUsuarioVersion2Repository->paginate([
                "typeData" => "all",
            ]);

            $sexos = $this->sexoRepository->paginate([
                "typeData" => "all",
            ]);

            $paises = $this->paisRepository->paginate([
                "typeData" => "all",
            ]);

            $municipios = $this->municipioRepository->paginate([
                "typeData" => "all",
            ]);

            $zonaVersion2 = $this->zonaVersion2Repository->paginate([
                "typeData" => "all",
            ]);

            $file = $request->file('archiveXlsx');

            $file_path = $file->getRealPath();

            if (!ImportXlsxValidator::validate($user_id, $keyErrorRedis, $file_path, 14, 'patient')) { 
                ProgressCircular::dispatch("xlsx_import_progress_patient.{$user_id}", 100);
                $errors = ErrorCollector::getErrors($keyErrorRedis);  // Obtener lista de errores

                // Convert array to JSON
                $routeJson = null;
                if (count($errors) > 0) {
                    $nameFile = 'error_' . $user_id . '.json';
                    $routeJson = 'companies/company_' . $company_id . '/patient/errors/' . $nameFile; // Ruta donde se guardará la carpeta
                    Storage::disk(Constants::DISK_FILES)->put($routeJson, json_encode($errors, JSON_PRETTY_PRINT));
                }

                // Enviar notificación al usuario
                $title = 'Importación de pacientes';
                $subtitle = 'Se encontraron errores en la estructura del archivo que esta intentando importar.';

                $this->sendNotification(
                    $user_id,
                    [
                        'title' => $title,
                        'subtitle' => $subtitle,
                        'data_import' => $errors,
                    ]
                );

                // Emitir errores al front
                ModalError::dispatch("patientStructureModalErrors.{$user_id}", $routeJson);

                return [
                    'code' => 422,
                    'errors' => $errors,
                ];
            } else {
                $xlsx = Excel::import(new PatientMasiveImport($user_id, $company_id, $tipoIdPisis, $ripsTipoUsuarioVersion2, $sexos, $paises, $municipios, $zonaVersion2), $request->file('archiveXlsx'));

                return [
                    'code' => 200,
                    'xlsx' => $xlsx,
                ];
            }
        });
    }

    
    private function sendNotification($userId, $data)
    {
        // Obtener el objeto User a partir del ID
        $user = $this->userRepository->find($userId);

        if ($user) {
            // Enviar notificación
            $user->notify(new BellNotification($data));

            // Enviar el correo usando el job de Brevo
            BrevoProcessSendEmail::dispatch(
                emailTo: [
                    [
                        "name" => $user->full_name,
                        "email" => $user->email,
                    ]
                ],
                subject: $data['title'],
                templateId: 16,  // El ID de la plantilla de Brevo que quieres usar
                params: [
                    "full_name" => $user->full_name,
                    "subtitle" => $data['subtitle'],
                    "bussines_name" => $user->company?->name,
                    "data_import" => $data['data_import'],
                    "show_table_errors" => count($data['data_import']) > 0 ? true : false,
                ],
            );
        }
    }
    
    public function getContentJson(Request $request)
    {
        return $this->execute(function () use ($request) {
            // Obtener el contenido del archivo
            $jsonContent = openFileJson($request["url_json"]);

            return [
                'code' => 200,
                'data' => $jsonContent,
            ];
        });
    }
    
    public function excelErrorsValidation(Request $request)
    {
        return $this->execute(function () use ($request) {

            $user_id = $request->input('user_id');

            // Obtener los mensajes de errores de las validaciones
            $data = $this->patientRepository->getValidationsErrorMessages($user_id);

            // Excluir el campo 'data' de cada elemento
            $filteredData = collect($data)->map(function ($item) {
                return collect($item)->except('data')->toArray();
            });

            $excel = Excel::raw(new PatientExcelErrorsValidationInfoExport($filteredData, false, true), \Maatwebsite\Excel\Excel::XLSX);

            $excelBase64 = base64_encode($excel);

            return [
                'code' => 200,
                'excel' => $excelBase64,
            ];
        });
    }
    
    public function exportExcelErrorsValidation(Request $request)
    {
        return $this->execute(function () use ($request) {

            $user_id = $request->input('user_id');

            // Obtener los mensajes de errores de las validaciones
            $data = $this->patientRepository->getValidationsErrorMessages($user_id);

            // Agrupar por 'row'
            $groupedErrors = collect($data)->groupBy('row');

            // Obtener un solo 'data' por grupo (el primero, por ejemplo)
            $result = $groupedErrors->map(function ($group) {
                // Tomar el primer elemento del grupo y devolver solo su 'data'
                return $group->first()['data'] ?? null;
            })->values();

            // Generar el XLSX con Laravel Excel
            $xlsx = Excel::raw(new PatientFormatExcelExport($result), \Maatwebsite\Excel\Excel::XLSX);

            $excelBase64 = base64_encode($xlsx);

            return [
                'code' => 200,
                'excel' => $excelBase64,
            ];
        });
    }
}
