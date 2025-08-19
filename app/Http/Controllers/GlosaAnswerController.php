<?php

namespace App\Http\Controllers;

use App\Enums\GlosaAnswer\StatusGlosaAnswerEnum;
use App\Helpers\Constants;
use App\Http\Requests\GlosaAnswer\GlosaAnswerStoreRequest;
use App\Http\Resources\GlosaAnswer\GlosaAnswerFormResource;
use App\Http\Resources\GlosaAnswer\GlosaAnswerPaginateResource;
use App\Repositories\GlosaAnswerRepository;
use App\Repositories\GlosaRepository;
use App\Repositories\ServiceRepository;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class GlosaAnswerController extends Controller
{
    use HttpResponseTrait;

    public function __construct(
        protected GlosaAnswerRepository $answerRepository,
        protected GlosaRepository $glosaRepository,
        protected ServiceRepository $serviceRepository,
        protected QueryController $queryController,
    ) {}

    public function paginate(Request $request)
    {
        return $this->execute(function () use ($request) {
            $data = $this->answerRepository->paginate($request->all());
            $tableData = GlosaAnswerPaginateResource::collection($data);

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

    public function create(Request $request)
    {
        return $this->execute(function () use ($request) {

            $statusGlosaAnswerEnumValues = array_map(function ($case) {
                return [
                    'value' => $case->value,
                    'title' => $case->description(),
                ];
            }, StatusGlosaAnswerEnum::cases());

            $glosa = $this->glosaRepository->find($request->input('glosa_id'));
            $glosa_date = Carbon::parse($glosa->date)->format('Y-m-d H:i');

            $codeGlosaAnswer = $this->queryController->selectCodeGlosaAnswer(request());

            return [
                'code' => 200,
                'statusGlosaAnswerEnumValues' => $statusGlosaAnswerEnumValues,
                'glosa_date' => $glosa_date,
                ...$codeGlosaAnswer,
            ];
        });
    }

    public function store(GlosaAnswerStoreRequest $request)
    {
        return $this->runTransaction(function () use ($request) {
            $post = $request->except(['file']);
            $answer = $this->answerRepository->store($post);

            if ($request->file('file')) {
                $file = $request->file('file');
                $ruta = 'companies/company_' . $answer->company_id . '/respuestas/respuesta_' . $answer->id . $request->input('file');

                $file = $file->store($ruta, Constants::DISK_FILES);
                $answer->file = $file;
                $answer->save();
            }

            return [
                'code' => 200,
                'message' => 'Respuesta agregada correctamente',
            ];
        });
    }

    public function edit(Request $request, $id)
    {
        return $this->execute(function () use ($request, $id) {

            $answer = $this->answerRepository->find($id);
            $form = new GlosaAnswerFormResource($answer);

            $statusGlosaAnswerEnumValues = array_map(function ($case) {
                return [
                    'value' => $case->value,
                    'title' => $case->description(),
                ];
            }, StatusGlosaAnswerEnum::cases());
            $glosa = $this->glosaRepository->find($request->input('glosa_id'));
            $glosa_date = Carbon::parse($glosa->date)->format('Y-m-d H:i');
            
            $codeGlosaAnswer = $this->queryController->selectCodeGlosaAnswer(request());

            return [
                'code' => 200,
                'form' => $form,
                'statusGlosaAnswerEnumValues' => $statusGlosaAnswerEnumValues,
                'glosa_date' => $glosa_date,
                ...$codeGlosaAnswer,
            ];
        });
    }

    public function update(GlosaAnswerStoreRequest $request, $id)
    {
        return $this->runTransaction(function () use ($request) {

            $post = $request->except(['file']);
            $answer = $this->answerRepository->store($post);

            if ($request->file('file')) {
                $file = $request->file('file');
                $ruta = 'companies/company_' . $answer->company_id . '/respuestas/respuesta_' . $answer->id . $request->input('file');

                $file = $file->store($ruta, Constants::DISK_FILES);
                $answer->file = $file;
                $answer->save();
            }

            return [
                'code' => 200,
                'message' => 'Respuesta modificada correctamente',
            ];
        });
    }

    public function delete($id)
    {
        return $this->runTransaction(function () use ($id) {
            $answer = $this->answerRepository->find($id);
            if ($answer) {

                $answer->delete();

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
}
