<?php

namespace App\Filament\Resources\MemberContentResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\MemberContentResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\MemberContentResource\Api\Transformers\MemberContentTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = MemberContentResource::class;


    /**
     * Show MemberContent
     *
     * @param Request $request
     * @return MemberContentTransformer
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

        return new MemberContentTransformer($query);
    }
}
