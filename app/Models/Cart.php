<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';

    protected $fillable = [
        'user_id',
    ];


    //relation
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
}


