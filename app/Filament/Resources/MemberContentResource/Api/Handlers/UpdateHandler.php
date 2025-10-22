<?php
namespace App\Filament\Resources\MemberContentResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\MemberContentResource;
use App\Filament\Resources\MemberContentResource\Api\Requests\UpdateMemberContentRequest;

class UpdateHandler extends Handlers
{
    public static string|null $uri = '/{id}';
    public static string|null $resource = MemberContentResource::class;
    public static bool $public = true;
    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel()
    {
        return static::$resource::getModel();
    }


    /**
     * Update MemberContent
     *
     * @param UpdateMemberContentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateMemberContentRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) {
            return static::sendNotFoundResponse();
        }

        $data = $request->except('file_path');
        $model->fill($data);
        $model->save();

        if ($request->hasFile('file_path')) {
            $path = $request->file('file_path')->store('job_images', 'public');

            $model->attachments()->delete();

            $model->attachments()->create([
                'file_path' => $path,
            ]);
        }

        return static::sendSuccessResponse($model->fresh('attachments'), "Successfully Updated Resource");
    }

}