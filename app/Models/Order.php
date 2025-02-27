<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total_amount',
        'payment_method',
        'address',
        'status',
    ];

    protected $with = ['items'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_items')->withPivot(['quantity', 'price']);;
    }

    public function getTotalPrice(): float
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }
}
