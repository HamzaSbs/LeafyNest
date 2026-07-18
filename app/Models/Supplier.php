<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $table = 'suppliers';
    protected $primaryKey = 'supplier_id';

    protected $fillable = ['name', 'contact'];

    public function plants(): HasMany
    {
        return $this->hasMany(Plant::class, 'supplier_id', 'supplier_id');
    }
}
