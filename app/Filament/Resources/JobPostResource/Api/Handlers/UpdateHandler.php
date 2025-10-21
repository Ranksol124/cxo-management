<?php
namespace App\Filament\Resources\JobPostResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\JobPostResource;
use App\Filament\Resources\JobPostResource\Api\Requests\UpdateJobPostRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = JobPostResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update JobPost
     *
     * @param UpdateJobPostRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateJobPostRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}