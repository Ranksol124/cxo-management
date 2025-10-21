<?php
namespace App\Filament\Resources\JobPostResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\JobPost;

/**
 * @property JobPost $resource
 */
class JobPostTransformer extends JsonResource
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
