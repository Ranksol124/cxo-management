<?php
namespace App\Filament\Resources\JobContentResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\JobContentResource;
use App\Filament\Resources\JobContentResource\Api\Requests\CreateJobContentRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = JobContentResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create JobContent
     *
     * @param CreateJobContentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateJobContentRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}