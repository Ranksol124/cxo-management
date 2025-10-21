<?php
namespace App\Filament\Resources\MemberContentResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\MemberContentResource;
use App\Filament\Resources\MemberContentResource\Api\Requests\CreateMemberContentRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = MemberContentResource::class;
  public static bool $public = true;
    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create MemberContent
     *
     * @param CreateMemberContentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateMemberContentRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}