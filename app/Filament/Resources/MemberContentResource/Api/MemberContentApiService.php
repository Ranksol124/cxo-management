<?php
namespace App\Filament\Resources\MemberContentResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\MemberContentResource;
use Illuminate\Routing\Router;


class MemberContentApiService extends ApiService
{
    protected static string | null $resource = MemberContentResource::class;

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
