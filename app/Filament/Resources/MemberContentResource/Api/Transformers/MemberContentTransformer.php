<?php
namespace App\Filament\Resources\MemberContentResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\MemberContent;

/**
 * @property MemberContent $resource
 */
class MemberContentTransformer extends JsonResource
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
