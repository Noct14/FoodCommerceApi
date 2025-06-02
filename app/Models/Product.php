<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
    'seller_id',
    'product_name',
    'price',
    'category',
    'description',
    'stock',
    'image_url',
    ];

    protected $appends = [
        'has_options',
        'price_formatted'
    ];

    //Json array
    public function toArray()
    {
        return [
            'id' => $this->id,
            'seller_id' => $this->seller_id,
            'product_name' => $this->product_name,
            'price' => $this->price_formatted,
            'category' => $this->category,
            'description' => $this->description,
            'stock' => $this->stock,
            'image_url' => $this->image_url,

            'has_options' => $this->has_options,
            'option_groups' => $this->optionGroups,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    // Accessor
    public function getHasOptionsAttribute()
    {
        return $this->optionGroups()->exists();
    }

    public function getPriceFormattedAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    //relation
    public function optionGroups()
    {
        return $this->hasMany(OptionGroup::class);
    }
}
