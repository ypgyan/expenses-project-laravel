<?php

namespace App\Http\Resources\Core\Expenses;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ExpenseResource extends JsonResource
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
            "category" => $this->category->name,
            "paid_at" => Carbon::createFromFormat('Y-m-d', $this->paid_at)->format('d-m-Y'),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
