<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mechanic_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->string('job_card_no')->unique()->nullable();
            $table->enum('service_type', ['Regular','Full'])->default('Regular');
            $table->enum('status', ['pending','in-progress','completed','cancelled'])->default('pending');
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->unsignedInteger('mileage_in')->default(0);
            $table->unsignedInteger('mileage_out')->nullable();
            $table->date('service_date');
            $table->date('next_service_date')->nullable();
            $table->unsignedInteger('next_service_km')->nullable();
            $table->text('customer_complaint')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('estimated_completion')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->decimal('labour_charge', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('vat_amount', 10, 2)->default(0);
            $table->boolean('invoice_generated')->default(false);
            $table->string('invoice_number')->nullable()->unique();
            $table->timestamp('invoice_date')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('services'); }
};