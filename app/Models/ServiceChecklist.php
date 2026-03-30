<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ServiceChecklist extends Model {
    protected $table    = 'service_checklists';
    protected $fillable = [
        'service_id','change_oil_filter','check_all_fluids',
        'check_tyre_pressure','check_tyre_condition','check_belts_hoses',
        'lubricate_chassis','check_brakes','check_wheel_bearings',
        'check_engine_thermostat','replace_spark_plugs','inspect_cooling_system',
        'check_leaks','change_air_filter','check_battery','check_brake_fluid',
        'check_washer_fluid','check_wiper_blades','check_lights',
        'check_exhaust','check_shock_absorbers','check_transmission_fluid',
        'additional_notes',
    ];
    protected $casts = [
        'change_oil_filter'=>'boolean','check_all_fluids'=>'boolean',
        'check_tyre_pressure'=>'boolean','check_tyre_condition'=>'boolean',
        'check_belts_hoses'=>'boolean','lubricate_chassis'=>'boolean',
        'check_brakes'=>'boolean','check_wheel_bearings'=>'boolean',
        'check_engine_thermostat'=>'boolean','replace_spark_plugs'=>'boolean',
        'inspect_cooling_system'=>'boolean','check_leaks'=>'boolean',
        'change_air_filter'=>'boolean','check_battery'=>'boolean',
        'check_brake_fluid'=>'boolean','check_washer_fluid'=>'boolean',
        'check_wiper_blades'=>'boolean','check_lights'=>'boolean',
        'check_exhaust'=>'boolean','check_shock_absorbers'=>'boolean',
        'check_transmission_fluid'=>'boolean',
    ];
    public function service() { return $this->belongsTo(Service::class); }
}