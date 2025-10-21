<?php
namespace App\Filament\Resources\MemberFeedResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\MemberFeedResource;
use App\Filament\Resources\MemberFeedResource\Api\Requests\UpdateMemberFeedRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = MemberFeedResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update MemberFeed
     *
     * @param UpdateMemberFeedRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateMemberFeedRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}