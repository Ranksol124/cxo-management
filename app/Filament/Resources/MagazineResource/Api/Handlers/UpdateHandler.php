<?php
namespace App\Filament\Resources\MagazineResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\MagazineResource;
use App\Filament\Resources\MagazineResource\Api\Requests\UpdateMagazineRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = MagazineResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update Magazine
     *
     * @param UpdateMagazineRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateMagazineRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}