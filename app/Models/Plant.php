<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plant extends Model
{
    protected $table = 'plants';
    protected $primaryKey = 'plant_id';

    protected $fillable = [
        'name',
        'category_id',
        'supplier_id',
        'price',
        'stock_qty',
        'sunlight', 'pot_size', 'season',
        'description',
        'care_instructions',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_qty' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'plant_id', 'plant_id');
    }
}
