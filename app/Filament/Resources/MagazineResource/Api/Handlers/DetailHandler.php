<?php

namespace App\Filament\Resources\MagazineResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\MagazineResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\MagazineResource\Api\Transformers\MagazineTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = MagazineResource::class;


    /**
     * Show Magazine
     *
     * @param Request $request
     * @return MagazineTransformer
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

        return new MagazineTransformer($query);
    }
}
