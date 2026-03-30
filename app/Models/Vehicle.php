<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model {
    protected $fillable = [
        'customer_id','registration_no','make','model','year',
        'color','chassis_no','category','current_mileage',
        'next_service_date','next_service_km',
    ];
    protected $casts = ['next_service_date' => 'date', 'year' => 'integer'];

    public function customer()    { return $this->belongsTo(Customer::class); }
    public function services()    { return $this->hasMany(Service::class); }
    public function lastService() { return $this->hasOne(Service::class)->latestOfMany(); }
    public function isOverdue(): bool {
        return $this->next_service_date && $this->next_service_date->isPast();
    }
}