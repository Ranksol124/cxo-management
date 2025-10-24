<?php
namespace App\Filament\Resources\SpotlightResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

/**
 * @property Spotlight $resource
 */
class SpotlightTransformer extends JsonResource
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
