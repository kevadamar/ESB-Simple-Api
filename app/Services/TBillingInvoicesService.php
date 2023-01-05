<?php

namespace App\Services;

use App\Models\TBillingInvoiceDetails;
use App\Models\TBillingInvoices;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class TBillingInvoicesService extends BaseService
{
    private $tBillingInvoices;
    private $tBillingInvoiceDetails;

    public function __construct(TBillingInvoices $tBillingInvoices, TBillingInvoiceDetails $tBillingInvoiceDetails)
    {
        $this->tBillingInvoices = $tBillingInvoices;
        $this->tBillingInvoiceDetails = $tBillingInvoiceDetails;
    }

    public function list(Request $request)
    {
        $page = $request->size ?? 10;
        $with = ['from_destination:id,code,name,description', 'to_destination:id,code,name,description', 'detail:id,t_billing_invoice_id,m_service_id,quantity,total_subamount', 'detail.service:id,m_service_type_id,code,description,unit_price', 'detail.service.service_type:id,code,description'];
        return $this->tBillingInvoices->with($with)->filter($request)->orderBy('id', 'desc')->paginate($page);
    }

    public function get($id)
    {
        $with = ['from_destination:id,code,name,description', 'to_destination:id,code,name,description', 'detail:id,t_billing_invoice_id,m_service_id,quantity,total_subamount', 'detail.service:id,m_service_type_id,code,description,unit_price', 'detail.service.service_type:id,code,description'];
        return $this->tBillingInvoices->with($with)->find($id);
    }


    public function storeInvoice(array $payload = [])
    {
        $payloadInvoices = $payload;
        unset($payloadInvoices['detail']);
        $payloadInvoiceDetails = [];

        $getLastInvNo = $this->tBillingInvoices->select('invoice_no')->limit(1)->orderBy('id', 'desc')->get();

        if (count($getLastInvNo->toArray()) == 0) {
            $counter = 1;
        } else {
            $counter = (int) $getLastInvNo[0]['invoice_no'] + 1;
        }

        $invNo = str_pad($counter, 4, "0", STR_PAD_LEFT);

        $payloadInvoices['invoice_no'] = $invNo;
        $totalAmount = 0;
        foreach ($payload['detail'] as $key => $value) {
            $subTotalAmount = $value['price'] * $value['quantity'];

            $temp['m_service_id'] = $value['m_service_id'];
            $temp['quantity'] = $value['quantity'];
            $temp['total_subamount'] = $subTotalAmount;
            $temp['created_at'] = Carbon::now();
            $temp['updated_at'] = Carbon::now();
            array_push($payloadInvoiceDetails, $temp);

            $totalAmount += $subTotalAmount;
        }

        $payloadInvoices['total_before_tax'] = $totalAmount;
        $payloadInvoices['total_tax'] = ($payloadInvoices['percent_tax'] / 100) * $totalAmount;
        $payloadInvoices['total_after_tax'] = $totalAmount + $payloadInvoices['total_tax'];
        $payloadInvoices['total_payment'] = $payloadInvoices['total_payment'] ?? 0;

        $store = $this->save($this->tBillingInvoices, $payloadInvoices);

        foreach ($payloadInvoiceDetails as $key => $value) {
            $payloadInvoiceDetails[$key]['t_billing_invoice_id'] = $store->id;
        }

        $store['detail'] = $payloadInvoiceDetails;

        if (!$this->saveBulk($this->tBillingInvoiceDetails, $payloadInvoiceDetails)) {
            throw new Exception("Failed Save Invoices. Please Contact Administrator", 500);
        }

        return $store;
    }

    public function updateInvoice(array $payload = [], $id = null)
    {
        $payloadInvoices = $payload;
        unset($payloadInvoices['detail']);
        $payloadInvoiceDetails = [];

        $totalAmount = 0;
        $this->destroyBulk($this->tBillingInvoiceDetails, 't_billing_invoice_id', $id);

        foreach ($payload['detail'] as $key => $value) {
            $subTotalAmount = $value['price'] * $value['quantity'];

            $temp['m_service_id'] = $value['m_service_id'];
            $temp['quantity'] = $value['quantity'];
            $temp['total_subamount'] = $subTotalAmount;
            $temp['created_at'] = Carbon::now();
            $temp['updated_at'] = Carbon::now();
            $temp['t_billing_invoice_id'] = $id;

            array_push($payloadInvoiceDetails, $temp);

            $totalAmount += $subTotalAmount;
        }

        if (!$this->saveBulk($this->tBillingInvoiceDetails, $payloadInvoiceDetails)) {
            throw new Exception("Failed Save Invoices. Please Contact Administrator", 500);
        }


        $payloadInvoices['total_before_tax'] = $totalAmount;
        $payloadInvoices['total_tax'] = ($payloadInvoices['percent_tax'] / 100) * $totalAmount;
        $payloadInvoices['total_after_tax'] = $totalAmount + $payloadInvoices['total_tax'];
        $payloadInvoices['total_payment'] = $payloadInvoices['total_payment'] ?? 0;

        $this->update($this->tBillingInvoices, $payloadInvoices, $id);

        $store['detail'] = $payloadInvoiceDetails;

        return $store;
    }


    public function destroyInvoice($id)
    {
        $this->destroy($this->tBillingInvoices, $id);

        $this->destroyBulk($this->tBillingInvoiceDetails, 't_billing_invoice_id', $id);
        return true;
    }
}
