<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('stock_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('part_number')->nullable()->unique();
            $table->string('category')->default('General');
            $table->string('supplier')->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('reorder_level')->default(5);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('selling_price', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_items');
    }
};
