<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemOption extends Model
{
    protected $fillable = [
        'order_item_id',
        'option_id',
    ];

    public $timestamps = false;

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}

