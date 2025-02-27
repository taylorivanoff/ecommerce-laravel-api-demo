<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierOrderItemResource extends JsonResource
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
            'supplier_amount' => $this->supplier_amount,
            'total_amount' => $this->total_amount,
            'payment_method' => $this->payment_method,
            'address' => $this->address,
            'created_at' => $this->created_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'items' => ProductResource::collection($this->whenLoaded('items'))
        ];
    }
}
