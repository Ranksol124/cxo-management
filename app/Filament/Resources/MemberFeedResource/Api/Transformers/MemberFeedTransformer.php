<?php
namespace App\Filament\Resources\MemberFeedResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\MemberFeed;

/**
 * @property MemberFeed $resource
 */
class MemberFeedTransformer extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->toArray();
    }
}
