<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = [
        'option_group_id',
        'name',
        'additional_price'
    ];

    //relation
    public function group()
    {
        return $this->belongsTo(OptionGroup::class, 'option_group_id');
    }

    public function cartItems()
    {
        return $this->belongsToMany(CartItem::class, 'cart_item_options');
    }

}
