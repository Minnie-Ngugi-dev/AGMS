<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model {
    protected $fillable = ['service_id','diagnosis','action_taken','cost','status','notes'];
    protected $casts    = ['cost' => 'decimal:2'];

    public function service() { return $this->belongsTo(Service::class); }
}