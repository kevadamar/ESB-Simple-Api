<?php

namespace App\Http\Controllers;

use App\Models\MServices;
use App\Services\ApiResponse;
use App\Services\MServicesService;
use Illuminate\Http\Request;

class MServicesController extends Controller
{
    private $mServiceTypesService;
    private $api;
    private $model;

    public function __construct(MServices $model, ApiResponse $api, MServicesService $mServiceTypesService)
    {
        $this->mServiceTypesService = $mServiceTypesService;
        $this->model = $model;
        $this->api = $api;
    }

    public function index(Request $request)
    {
        try {
            $results = $this->mServiceTypesService->list($request);
            return $this->api->list($results, $this->model);
        } catch (\Throwable $th) {
            return $this->api->error_code_log("Internal Server Error", $th->getMessage(), $th->getCode());
        }
    }

    public function store(Request $request)
    {
        try {
            $this->mServiceTypesService->validated($request->all(), [
                'code' => "required|unique:m_services",
                'm_service_type_id' => 'required|numeric',
                'description' => 'required',
                'unit_price' => 'required|numeric',
            ]);

            $store = $this->mServiceTypesService->save($this->model, $request->all());

            return $this->api->store($store);
        } catch (\Throwable $th) {
            return $this->api->error_code_log("Internal Server Error", $th->getMessage(), $th->getCode());
        }
    }

    public function show($id)
    {
        try {
            $result = $this->mServiceTypesService->get($id);
            return $this->api->success($result);
        } catch (\Throwable $th) {
            return $this->api->error_code_log("Internal Server Error", $th->getMessage(), $th->getCode());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->mServiceTypesService->validated($request->all(), [
                'code' => "nullable|unique:m_services,code,$id",
                'm_service_type_id' => 'nullable',
                'description' => 'nullable',
                'unit_price' => 'nullable',
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
