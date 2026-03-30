<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceOrderItem extends Model
{
    protected $fillable = [
        'service_id','service_item_id','name','price','quantity','notes'
    ];
    protected $casts = ['price' => 'decimal:2'];

    public function service() {
        return $this->belongsTo(Service::class);
    }

    public function serviceItem() {
        return $this->belongsTo(ServiceItem::class);
    }

    public function getTotalAttribute(): float {
        return $this->price * $this->quantity;
    }
}