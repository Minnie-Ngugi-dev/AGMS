<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleMake extends Model
{
    protected $fillable = ['name', 'models', 'is_active'];
    protected $casts = ['models' => 'array', 'is_active' => 'boolean'];

    public function getModelsListAttribute(): array
    {
        return $this->models ?? [];
    }
}