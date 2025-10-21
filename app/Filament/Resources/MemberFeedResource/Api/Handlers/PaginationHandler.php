<?php
namespace App\Filament\Resources\MemberFeedResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filament\Resources\MemberFeedResource;
use App\Filament\Resources\MemberFeedResource\Api\Transformers\MemberFeedTransformer;

class PaginationHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = MemberFeedResource::class;


    /**
     * List of MemberFeed
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function handler()
    {
        $query = static::getEloquentQuery();

        $query = QueryBuilder::for($query)
        ->allowedFields($this->getAllowedFields() ?? [])
        ->allowedSorts($this->getAllowedSorts() ?? [])
        ->allowedFilters($this->getAllowedFilters() ?? [])
        ->allowedIncludes($this->getAllowedIncludes() ?? [])
        ->paginate(request()->query('per_page'))
        ->appends(request()->query());

        return MemberFeedTransformer::collection($query);
    }
}
