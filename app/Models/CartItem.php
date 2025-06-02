<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_items';

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'note'
    ];

    public function calculateSubtotal()
    {
        $basePrice = $this->product->price;
        $optionPrice = $this->options->sum('additional_price');
        return ($basePrice + $optionPrice) * $this->quantity;
    }

    public function options()
    {
        return $this->belongsToMany(Option::class, 'cart_item_options');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
