<?php

namespace App\Services;

use App\Models\MServices;
use Illuminate\Http\Request;

class MServicesService extends BaseService
{
    private $mServices;

    public function __construct(MServices $mServices)
    {
        $this->mServices = $mServices;
    }

    public function list(Request $request)
    {
        $page = $request->size ?? 10;
        $with = ['service_type:id,code,description'];
        return $this->mServices->with($with)->filter($request)->orderBy('id', 'desc')->paginate($page);
    }

    public function get($id)
    {
        return $this->mServices->find($id);
    }
}
