<?php
namespace App\Filament\Resources\SpotlightResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\SpotlightResource;
use App\Filament\Resources\SpotlightResource\Api\Requests\CreateSpotlightRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = SpotlightResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create Spotlight
     *
     * @param CreateSpotlightRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateSpotlightRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}