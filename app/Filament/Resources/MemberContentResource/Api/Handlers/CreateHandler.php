<?php
namespace App\Filament\Resources\MemberContentResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\MemberContentResource;
use App\Filament\Resources\MemberContentResource\Api\Requests\CreateMemberContentRequest;

class CreateHandler extends Handlers
{
    public static string|null $uri = '/';
    public static string|null $resource = MemberContentResource::class;
    public static bool $public = true;
    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel()
    {
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

        $data = $request->except('file_path');
        $model->fill($data);
        $model->save();

        if ($request->hasFile('file_path')) {
            $path = $request->file('file_path')->store('job_images', 'public');
            $model->attachments()->create([
                'file_path' => $path,
            ]);
        }

        return static::sendSuccessResponse($model->fresh('attachments'), "Successfully Created Resource");
    }

}