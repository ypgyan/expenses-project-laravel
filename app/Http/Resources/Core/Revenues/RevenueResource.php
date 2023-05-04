<?php

namespace App\Http\Resources\Core\Revenues;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RevenueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "description" => $this->description,
            "value" => $this->value,
            "received_at" => $this->received_at->format('d-m-Y'),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
