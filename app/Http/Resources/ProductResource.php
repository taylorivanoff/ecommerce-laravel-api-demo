<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'image_url' => $this->image_url,
            'supplier' => new SupplierResource($this->whenLoaded('supplier')),
            'order_item' => $this->whenPivotLoaded('order_items', function () {
                return [
                    'sold_at_price' => $this->pivot->price,
                    'quantity' => $this->pivot->quantity,
                ];
            }),
        ];
    }
}
