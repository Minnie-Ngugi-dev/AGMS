<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('registration_no')->unique();
            $table->string('make');
            $table->string('model');
            $table->year('year')->nullable();
            $table->string('color')->nullable();
            $table->string('chassis_no')->nullable()->unique();
            $table->enum('category', ['Car','Van','Mini Truck','Truck','Trailer'])->default('Car');
            $table->unsignedInteger('current_mileage')->default(0);
            $table->date('next_service_date')->nullable();
            $table->unsignedInteger('next_service_km')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('vehicles'); }
};