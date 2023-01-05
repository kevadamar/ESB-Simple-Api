<?php

namespace App\Services;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class BaseService
{
    public function validated($request = [], array $rules = [])
    {
        $validator = Validator::make($request, $rules);

        if ($validator->fails()) {
            throw new Exception($validator->errors(), 400);
        }
        return;
    }

    public function save($model, array $payload = [])
    {
        return $model->create($payload);
    }

    public function saveBulk($model, array $payload = [])
    {
        return $model->insert($payload);
    }

    public function update($model, array $payload = [], int $id = null)
    {
        try {
            $updateData = $model->findOrFail($id);
            $updateData->update($payload);

            return $updateData;
        } catch (\Throwable $th) {
            if ($th instanceof ModelNotFoundException) {
                throw new Exception("Data not found", 404);
            }

            return $th;
        }
    }

    public function destroy($model, int $id)
    {
        try {
            $deleteData = $model->findOrFail($id);
            $deleteData->delete();
            return $deleteData;
        } catch (\Throwable $th) {
            if ($th instanceof ModelNotFoundException) {
                throw new Exception("Data not found", 404);
            }

            return $th;
        }
    }

    public function destroyBulk($model, $column = 'id', int $id = null)
    {
        try {
            $deleteData = $model->where($column, $id);
            $deleteData->delete();
            return $deleteData;
        } catch (\Throwable $th) {
            if ($th instanceof ModelNotFoundException) {
                throw new Exception("Data not found", 404);
            }

            return $th;
        }
    }
}
