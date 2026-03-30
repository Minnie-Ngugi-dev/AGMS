<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceItem extends Model
{
    protected $fillable = ['name', 'category', 'description', 'price', 'is_active'];
    protected $casts = ['price' => 'decimal:2', 'is_active' => 'boolean'];

    public function scopeActive($query) {
        return $query->where('is_active', true);
    }
}