<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plants', function (Blueprint $table) {
            $table->id('plant_id');
            $table->string('name');
            $table->foreignId('category_id')->constrained('categories', 'category_id')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('supplier_id')->constrained('suppliers', 'supplier_id')->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal('price', 10, 2);
            $table->integer('stock_qty')->default(0);
            $table->string('sunlight')->nullable();
            $table->string('pot_size')->nullable();
            $table->string('season')->nullable();
            $table->text('description')->nullable();
            $table->text('care_instructions')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};
