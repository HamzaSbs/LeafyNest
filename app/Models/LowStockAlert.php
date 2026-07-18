<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LowStockAlert extends Model
{
    protected $table = 'low_stock_alerts';
    protected $primaryKey = 'alert_id';

    protected $fillable = [
        'plant_id',
        'alert_date',
        'current_stock',
    ];

    protected $casts = [
        'alert_date' => 'date',
        'current_stock' => 'integer',
    ];

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class, 'plant_id', 'plant_id');
    }
}
