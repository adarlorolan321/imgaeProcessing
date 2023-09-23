<?php

namespace App\Http\Resources\Resolution;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResolutionListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
