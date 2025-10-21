<?php
namespace App\Filament\Resources\MagazineResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Magazine;

/**
 * @property Magazine $resource
 */
class MagazineTransformer extends JsonResource
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
