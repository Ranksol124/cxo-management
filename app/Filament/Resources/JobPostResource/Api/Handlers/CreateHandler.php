<?php
namespace App\Filament\Resources\JobPostResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\JobPostResource;
use App\Filament\Resources\JobPostResource\Api\Requests\CreateJobPostRequest;

class CreateHandler extends Handlers
{
    public static string|null $uri = '/';
    public static string|null $resource = JobPostResource::class;
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
     * Create JobPost
     *
     * @param CreateJobPostRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateJobPostRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}