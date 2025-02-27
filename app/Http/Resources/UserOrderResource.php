<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => ucwords($this->status),
            'total_amount' => (float) $this->total_amount,
            'payment_method' => $this->payment_method,
            'address' => $this->address,
            'created_at' => $this->created_at,
            'items' => ProductResource::collection($this->whenLoaded('items'))
        ];
    }
}
