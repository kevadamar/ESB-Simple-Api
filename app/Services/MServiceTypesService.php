<?php

namespace App\Services;

use App\Models\MServiceTypes;
use Illuminate\Http\Request;

class MServiceTypesService extends BaseService
{
    private $mServiceTypes;

    public function __construct(MServiceTypes $mServiceTypes)
    {
        $this->mServiceTypes = $mServiceTypes;
    }

    public function list(Request $request)
    {
        $page = $request->size ?? 10;
        return $this->mServiceTypes->filter($request)->orderBy('id', 'desc')->paginate($page);
    }

    public function get($id)
    {
        return $this->mServiceTypes->find($id);
    }
}
