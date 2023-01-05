<?php

namespace App\Utils;

use Illuminate\Support\Str;

class Helpers
{
    public static function Columns($arr = [])
    {
        $datas = collect();
        foreach ($arr as $key => $val) {
            $snake = Str::snake($key);
            if (is_array($val)) {
                $values = collect();
                foreach ($val as $k => $v) {
                    $values->push([
                        'label' => $v,
                        'value' => $k,
                    ]);
                }
                $data = [
                    'label' => $key,
                    'key' => $snake,
                    'type' => 'array',
                    'values' => $values->toArray(),
                ];
                $datas->push($data);
            } else {
                $data = [
                    'label' => $key,
                    'key' => $snake,
                    'type' => $val,
                ];
                $datas->push($data);
            }
        }

        return $datas;
    }
}
