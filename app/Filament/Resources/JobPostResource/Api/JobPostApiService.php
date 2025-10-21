<?php
namespace App\Filament\Resources\JobPostResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\JobPostResource;
use Illuminate\Routing\Router;


class JobPostApiService extends ApiService
{
    protected static string | null $resource = JobPostResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class
        ];

    }
}
