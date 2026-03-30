<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model {
    protected $fillable = ['name','phone','email','role','employee_id','specialization','is_active'];
    protected $casts    = ['is_active' => 'boolean'];

    public function services() { return $this->hasMany(Service::class, 'mechanic_id'); }
    public function scopeActive($q)    { return $q->where('is_active', true); }
    public function scopeMechanics($q) { return $q->where('role', 'mechanic'); }
}