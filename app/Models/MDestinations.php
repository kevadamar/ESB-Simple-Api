<?php

namespace App\Models;

use App\Utils\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MDestinations extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

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
                        $query->where($key, 'like', '%' . $val . '%');
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
        ]);
    }
}
