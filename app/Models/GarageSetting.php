<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GarageSetting extends Model {
    protected $fillable = [
        'garage_name','garage_phone','garage_email','garage_address',
        'kra_pin','logo','invoice_prefix','invoice_start','currency',
        'vat_enabled','vat_rate','regular_km','regular_days',
        'full_km','full_days','notify_regular','notify_full',
        'notify_complete','notify_overdue',
    ];
    protected $casts = [
        'vat_enabled'=>'boolean','notify_regular'=>'boolean',
        'notify_full'=>'boolean','notify_complete'=>'boolean',
        'notify_overdue'=>'boolean','vat_rate'=>'decimal:2',
    ];
    public static function get(): self {
        return self::firstOrCreate([], ['garage_name' => 'AutoMS Garage']);
    }
}