<?php

namespace App\Filament\Resources\EventResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\EventResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\EventResource\Api\Transformers\EventTransformer;

class DetailHandler extends Handlers
{
    public static string|null $uri = '/{id}';
    public static string|null $resource = EventResource::class;
    public static bool $public = true;

    /**
     * Show Event
     *
     * @param Request $request
     * @return EventTransformer
     */
    public function handler(Request $request)
    {
        $id = $request->route('id');

        $query = static::getEloquentQuery();

        $query = QueryBuilder::for(
            $query->where(static::getKeyName(), $id)
        )
            ->first();

        if (!$query)
            return static::sendNotFoundResponse();

        return new EventTransformer($query);
    }
}
