<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMServiceTypeRequest;
use App\Models\MServiceTypes;
use App\Services\ApiResponse;
use App\Services\MServiceTypesService;
use Illuminate\Http\Request;

class MServiceTypesController extends Controller
{
    private $mServiceTypesService;
    private $api;
    private $model;

    public function __construct(MServiceTypes $model, ApiResponse $api, MServiceTypesService $mServiceTypesService)
    {
        $this->mServiceTypesService = $mServiceTypesService;
        $this->model = $model;
        $this->api = $api;
    }

    public function index(Request $request)
    {
        $results = $this->mServiceTypesService->list($request);
        return $this->api->list($results,$this->model);
    }

    public function store(Request $request)
    {
        try {
            $this->mServiceTypesService->validated($request->all(), [
                'code' => "required|unique:m_service_types",
                'description' => 'required',
            ]);

            $store = $this->mServiceTypesService->save($this->model, $request->all());

            return $this->api->store($store);
        } catch (\Throwable $th) {
            return $this->api->error_code_log("Internal Server Error", $th->getMessage(), $th->getCode());
        }
    }

    public function show($id)
    {
        $result = $this->mServiceTypesService->get($id);
        return $this->api->success($result);
    }

    public function update(Request $request, $id)
    {
        try {
            $this->mServiceTypesService->validated($request->all(), [
                'code' => "required|unique:m_service_types,code,$id",
                'description' => 'nullable',
            ]);

            $result = $this->mServiceTypesService->update($this->model, $request->all(), $id);

            return $this->api->update($result);
        } catch (\Throwable $th) {
            return $this->api->error_code_log("Internal Server Error", $th->getMessage(), $th->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $deleted = $this->mServiceTypesService->destroy($this->model, $id);
            return $this->api->delete($deleted);
        } catch (\Throwable $th) {
            return $this->api->error_code_log("Internal Server Error", $th->getMessage(), $th->getCode());
        }
    }
}
