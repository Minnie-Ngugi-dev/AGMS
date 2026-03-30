<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('service_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->boolean('change_oil_filter')->default(false);
            $table->boolean('check_all_fluids')->default(false);
            $table->boolean('check_tyre_pressure')->default(false);
            $table->boolean('check_tyre_condition')->default(false);
            $table->boolean('check_belts_hoses')->default(false);
            $table->boolean('lubricate_chassis')->default(false);
            $table->boolean('check_brakes')->default(false);
            $table->boolean('check_wheel_bearings')->default(false);
            $table->boolean('check_engine_thermostat')->default(false);
            $table->boolean('replace_spark_plugs')->default(false);
            $table->boolean('inspect_cooling_system')->default(false);
            $table->boolean('check_leaks')->default(false);
            $table->boolean('change_air_filter')->default(false);
            $table->boolean('check_battery')->default(false);
            $table->boolean('check_brake_fluid')->default(false);
            $table->boolean('check_washer_fluid')->default(false);
            $table->boolean('check_wiper_blades')->default(false);
            $table->boolean('check_lights')->default(false);
            $table->boolean('check_exhaust')->default(false);
            $table->boolean('check_shock_absorbers')->default(false);
            $table->boolean('check_transmission_fluid')->default(false);
            $table->text('additional_notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('service_checklists'); }
};