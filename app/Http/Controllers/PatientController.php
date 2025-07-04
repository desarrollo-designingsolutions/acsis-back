<?php

namespace App\Http\Controllers;

use App\Exports\PatientExcelExport;
use App\Http\Requests\Patient\PatientStoreRequest;
use App\Http\Resources\Patient\PatientFormResource;
use App\Http\Resources\Patient\PatientListResource;
use App\Repositories\PatientRepository;
use App\Repositories\TypeEntityRepository;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PatientController extends Controller
{
    use HttpResponseTrait;

    public function __construct(
        protected PatientRepository $patientRepository,
        protected TypeEntityRepository $typeEntityRepository,
        protected QueryController $queryController,
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
}
