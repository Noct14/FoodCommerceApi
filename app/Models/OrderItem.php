<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'note'
    ];

    public function calculateSubtotal()
    {
        $basePrice = $this->price; // asumsinya udah disimpan saat checkout
        $optionPrice = $this->options->sum('additional_price');
        return $basePrice * $this->quantity;
    }

    //relation
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function options() {
        return $this->belongsToMany(Option::class, 'order_item_options');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
