<?php

namespace App\Models;

use App\Utils\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MServices extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function service_type()
    {
        return $this->belongsTo(MServiceTypes::class, 'm_service_type_id');
    }


    public function scopeFilter($query, $request)
    {
        if ($request->has('search')) {
            $key = $request->search;
            $query->where('code', 'like', '%' . $key . '%')
                ->orWhere('description', 'like', '%' . $key . '%');
        }

        foreach ($request->all() as $key => $val) {
            if ($key === 'search' || $key === 'size' || $key === 'page') {
            } else {
                if ($request->has($key)) {
                    if ($val !== null) {
                        switch ($key) {
                            case "service_type":
                                $query->whereHas('service_type', function ($query) use ($val) {
                                    $query->where('description', 'like', '%' . $val . '%');
                                });
                                break;

                            default:
                                $query->where($key, 'like', '%' . $val . '%');
                                break;
                        }
                    }
                }
            }
        }

        return $query;
    }

    // menambahkan column untuk api
    public static function columns()
    {
        return Helpers::columns([
            'Code' => 'string',
            'Description' => 'string',
            'Service Type' => 'string',
            'Unit Price' => 'string',
        ]);
    }
}
