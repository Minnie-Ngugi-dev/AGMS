<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model {
    protected $fillable = ['name','phone','email','address'];

    public function vehicles() { return $this->hasMany(Vehicle::class); }
    public function services() { return $this->hasManyThrough(Service::class, Vehicle::class); }
}