<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Part extends Model {
    protected $fillable = ['service_id','name','part_number','quantity','unit_price','notes'];
    protected $casts    = ['unit_price' => 'decimal:2', 'quantity' => 'integer'];

    public function service() { return $this->belongsTo(Service::class); }
    public function getTotalAttribute(): float {
        return $this->quantity * $this->unit_price;
    }
}