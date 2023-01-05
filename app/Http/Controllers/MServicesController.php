<?php

namespace App\Http\Controllers;

use App\Models\MServices;
use App\Services\ApiResponse;
use App\Services\MServicesService;
use Illuminate\Http\Request;

class MServicesController extends Controller
{
    private $mServicesService;
    private $api;
    private $model;

    public function __construct(MServices $model, ApiResponse $api, MServicesService $mServicesService)
    {
        $this->mServicesService = $mServicesService;
        $this->model = $model;
        $this->api = $api;
    }

    public function index(Request $request)
    {
        try {
            $results = $this->mServicesService->list($request);
            return $this->api->list($results, $this->model);
        } catch (\Throwable $th) {
            return $this->api->error_code_log("Internal Server Error", $th->getMessage(), $th->getCode());
        }
    }

    public function store(Request $request)
    {
        try {
            $this->mServicesService->validated($request->all(), [
                'code' => "required|unique:m_services",
                'm_service_type_id' => 'required|numeric',
                'description' => 'required',
                'unit_price' => 'required|numeric',
            ]);

            $store = $this->mServicesService->save($this->model, $request->all());
            return $this->api->store($store);
        } catch (\Throwable $th) {
            return $this->api->error_code_log("Internal Server Error", $th->getMessage(), $th->getCode());
        }
    }

    public function show($id)
    {
        try {
            $result = $this->mServicesService->get($id);
            return $this->api->success($result);
        } catch (\Throwable $th) {
            return $this->api->error_code_log("Internal Server Error", $th->getMessage(), $th->getCode());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->mServicesService->validated($request->all(), [
                'code' => "nullable|unique:m_services,code,$id",
                'm_service_type_id' => 'nullable',
                'description' => 'nullable',
                'unit_price' => 'nullable',
            ]);

            $result = $this->mServicesService->update($this->model, $request->all(), $id);

            return $this->api->update($result);
        } catch (\Throwable $th) {
            return $this->api->error_code_log("Internal Server Error", $th->getMessage(), $th->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $deleted = $this->mServicesService->destroy($this->model, $id);
            return $this->api->delete($deleted);
        } catch (\Throwable $th) {
            return $this->api->error_code_log("Internal Server Error", $th->getMessage(), $th->getCode());
        }
    }
}
