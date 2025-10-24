<?php

namespace App\Filament\Resources\SpotlightResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\SpotlightResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\SpotlightResource\Api\Transformers\SpotlightTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = SpotlightResource::class;


    /**
     * Show Spotlight
     *
     * @param Request $request
     * @return SpotlightTransformer
     */
    public function handler(Request $request)
    {
        $id = $request->route('id');
        
        $query = static::getEloquentQuery();

        $query = QueryBuilder::for(
            $query->where(static::getKeyName(), $id)
        )
            ->first();

        if (!$query) return static::sendNotFoundResponse();

        return new SpotlightTransformer($query);
    }
}
