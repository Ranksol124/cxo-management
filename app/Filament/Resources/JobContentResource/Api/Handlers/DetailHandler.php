<?php

namespace App\Filament\Resources\JobContentResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\JobContentResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\JobContentResource\Api\Transformers\JobContentTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = JobContentResource::class;


    /**
     * Show JobContent
     *
     * @param Request $request
     * @return JobContentTransformer
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

        return new JobContentTransformer($query);
    }
}
