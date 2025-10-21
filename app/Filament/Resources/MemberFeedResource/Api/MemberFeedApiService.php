<?php
namespace App\Filament\Resources\MemberFeedResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\MemberFeedResource;
use Illuminate\Routing\Router;


class MemberFeedApiService extends ApiService
{
    protected static string | null $resource = MemberFeedResource::class;

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
