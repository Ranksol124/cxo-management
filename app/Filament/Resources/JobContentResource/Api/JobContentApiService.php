<?php
namespace App\Filament\Resources\JobContentResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\JobContentResource;
use Illuminate\Routing\Router;


class JobContentApiService extends ApiService
{
    protected static string | null $resource = JobContentResource::class;

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
