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
                    'Label' => $key,
                    'type' => 'array',
                    'values' => $values->toArray(),
                ];
                $datas->push($data);
            } else {
                $data = [
                    'Label' => $key,
                    'type' => $val,
                ];
                $datas->push($data);
            }
        }

        return $datas;
    }
}
