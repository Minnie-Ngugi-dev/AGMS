<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('garage_settings', function (Blueprint $table) {
            $table->id();
            $table->string('garage_name')->default('AutoMS Garage');
            $table->string('garage_phone')->nullable();
            $table->string('garage_email')->nullable();
            $table->text('garage_address')->nullable();
            $table->string('kra_pin')->nullable();
            $table->string('logo')->nullable();
            $table->string('invoice_prefix')->default('INV');
            $table->unsignedInteger('invoice_start')->default(1001);
            $table->string('currency')->default('KSh');
            $table->boolean('vat_enabled')->default(true);
            $table->decimal('vat_rate', 5, 2)->default(16.00);
            $table->unsignedInteger('regular_km')->default(5000);
            $table->unsignedInteger('regular_days')->default(90);
            $table->unsignedInteger('full_km')->default(10000);
            $table->unsignedInteger('full_days')->default(180);
            $table->boolean('notify_regular')->default(true);
            $table->boolean('notify_full')->default(true);
            $table->boolean('notify_complete')->default(true);
            $table->boolean('notify_overdue')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('garage_settings'); }
};