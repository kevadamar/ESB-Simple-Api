<?php

namespace App\Services;

use App\Models\MDestinations;
use Illuminate\Http\Request;

class MDestinationsService extends BaseService
{
    private $mDestinations;

    public function __construct(MDestinations $mDestinations)
    {
        $this->mDestinations = $mDestinations;
    }

    public function list(Request $request)
    {
        $page = $request->size ?? 10;
        return $this->mDestinations->filter($request)->orderBy('id', 'desc')->paginate($page);
    }

    public function get($id)
    {
        return $this->mDestinations->find($id);
    }
}
