<?php

namespace App\Http\Controllers;

use App\Models\MDestinations;
use App\Services\ApiResponse;
use App\Services\MDestinationsService;
use Illuminate\Http\Request;

class MDestinationsController extends Controller
{
    private $mDestinations;
    private $api;
    private $model;

    public function __construct(MDestinations $model, ApiResponse $api, MDestinationsService $mDestinations)
    {
        $this->mDestinations = $mDestinations;
        $this->model = $model;
        $this->api = $api;
    }

    public function index(Request $request)
    {
        try {
            $results = $this->mDestinations->list($request);
            return $this->api->list($results, $this->model);
        } catch (\Throwable $th) {
            return $this->api->error_code_log("Internal Server Error", $th->getMessage(), $th->getCode());
        }
    }

    public function store(Request $request)
    {
        try {
            $this->mDestinations->validated($request->all(), [
                'code' => "required|unique:m_destinations",
                'description' => 'required',
            ]);

            $store = $this->mDestinations->save($this->model, $request->all());

            return $this->api->store($store);
        } catch (\Throwable $th) {
            return $this->api->error_code_log("Internal Server Error", $th->getMessage(), $th->getCode());
        }
    }

    public function show($id)
    {
        try {
            $result = $this->mDestinations->get($id);
            return $this->api->success($result);
        } catch (\Throwable $th) {
            return $this->api->error_code_log("Internal Server Error", $th->getMessage(), $th->getCode());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->mDestinations->validated($request->all(), [
                'code' => "nullable|unique:m_destinations,code,$id",
                'description' => 'nullable',
            ]);

            $result = $this->mDestinations->update($this->model, $request->all(), $id);

            return $this->api->update($result);
        } catch (\Throwable $th) {
            return $this->api->error_code_log("Internal Server Error", $th->getMessage(), $th->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $deleted = $this->mDestinations->destroy($this->model, $id);
            return $this->api->delete($deleted);
        } catch (\Throwable $th) {
            return $this->api->error_code_log("Internal Server Error", $th->getMessage(), $th->getCode());
        }
    }
}
