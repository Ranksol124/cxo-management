<?php
namespace App\Filament\Resources\SpotlightResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\SpotlightResource;
use App\Filament\Resources\SpotlightResource\Api\Requests\UpdateSpotlightRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = SpotlightResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update Spotlight
     *
     * @param UpdateSpotlightRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateSpotlightRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}