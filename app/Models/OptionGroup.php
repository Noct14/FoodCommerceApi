<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OptionGroup extends Model
{
    protected $fillable =
    [
        'product_id',
        'name',
        'is_required',
        'is_multiple',
        'max_selection'
    ];

    protected $casts =
    [
        'is_required' => 'boolean',
        'is_multiple' => 'boolean',
    ];

    public function toArray()
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'name' => $this->name,
            'is_required' => $this->is_required,
            'is_multiple' => $this->is_multiple,
            'options' => $this->options
        ];
    }

    //Relation
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }
}
