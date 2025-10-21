<?php
namespace App\Filament\Resources\MagazineResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\MagazineResource;
use Illuminate\Routing\Router;


class MagazineApiService extends ApiService
{
    protected static string | null $resource = MagazineResource::class;

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
