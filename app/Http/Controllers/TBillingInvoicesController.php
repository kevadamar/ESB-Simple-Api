<?php

namespace App\Http\Controllers;

use App\Models\TBillingInvoices;
use App\Services\ApiResponse;
use App\Services\TBillingInvoicesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TBillingInvoicesController extends Controller
{
    private $tBillingInvoicesService;
    private $api;
    private $model;


    public function __construct(TBillingInvoices $model, ApiResponse $api, TBillingInvoicesService $tBillingInvoicesService)
    {
        $this->tBillingInvoicesService = $tBillingInvoicesService;
        $this->model = $model;
        $this->api = $api;
    }

    public function index(Request $request)
    {
        try {
            $results = $this->tBillingInvoicesService->list($request);
            return $this->api->list($results, $this->model);
        } catch (\Throwable $th) {
            return $this->api->error_code_log("Internal Server Error", $th->getMessage(), $th->getCode());
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->tBillingInvoicesService->validated($request->all(), [
                'from_destination_id' => 'required|numeric',
                'to_destination_id' => 'required|numeric',
                'subject' => 'required',
                'percent_tax' => 'required|numeric',
                'detail.*.quantity' => 'required|numeric',
                'detail.*.price' => 'required|numeric',
                'detail.*.m_service_id' => 'required|numeric'
            ]);

            $store = $this->tBillingInvoicesService->storeInvoice($request->all());
            DB::commit();
            return $this->api->store($store);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->api->error_code_log("Internal Server Error", $th->getMessage(), $th->getCode());
        }
    }

    public function show($id)
    {
        try {
            $result = $this->tBillingInvoicesService->get($id);
            return $this->api->success($result);
        } catch (\Throwable $th) {
            return $this->api->error_code_log("Internal Server Error", $th->getMessage(), $th->getCode());
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $this->tBillingInvoicesService->validated($request->all(), [
                'from_destination_id' => 'required|numeric',
                'to_destination_id' => 'required|numeric',
                'subject' => 'required',
                'percent_tax' => 'required|numeric',
                'detail.*.quantity' => 'required|numeric',
                'detail.*.price' => 'required|numeric',
                'detail.*.m_service_id' => 'required|numeric'
            ]);

            $result = $this->tBillingInvoicesService->updateInvoice($request->all(), $id);
            DB::commit();
            return $this->api->update($result);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->api->error_code_log("Internal Server Error", $th->getMessage(), $th->getCode());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $deleted = $this->tBillingInvoicesService->destroyInvoice($id);
            DB::commit();
            return $this->api->delete($deleted);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->api->error_code_log("Internal Server Error", $th->getMessage(), $th->getCode());
        }
    }
}
