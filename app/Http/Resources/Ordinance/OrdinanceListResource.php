<?php

namespace App\Http\Resources\Ordinance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdinanceListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray( $request): array
    {
        return parent::toArray($request);
    }
}
