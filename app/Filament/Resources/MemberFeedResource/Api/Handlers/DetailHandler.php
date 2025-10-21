<?php

namespace App\Filament\Resources\MemberFeedResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\MemberFeedResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\MemberFeedResource\Api\Transformers\MemberFeedTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = MemberFeedResource::class;


    /**
     * Show MemberFeed
     *
     * @param Request $request
     * @return MemberFeedTransformer
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

        return new MemberFeedTransformer($query);
    }
}
