<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    protected $fillable = [
        'name','part_number','category','supplier',
        'quantity','reorder_level','unit_price',
        'selling_price','notes','is_active'
    ];
    protected $casts = [
        'unit_price'    => 'decimal:2',
        'selling_price' => 'decimal:2',
        'is_active'     => 'boolean',
    ];

    public function transactions() {
        return $this->hasMany(StockTransaction::class);
    }

    public function isLowStock(): bool {
        return $this->quantity <= $this->reorder_level;
    }

    public function scopeActive($query) {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query) {
        return $query->whereColumn('quantity', '<=', 'reorder_level');
    }
}