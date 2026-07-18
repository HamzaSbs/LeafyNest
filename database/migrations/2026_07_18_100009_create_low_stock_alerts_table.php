<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('low_stock_alerts', function (Blueprint $table) {
            $table->id('alert_id');
            $table->foreignId('plant_id')->constrained('plants', 'plant_id')->cascadeOnDelete();
            $table->date('alert_date');
            $table->integer('current_stock');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('low_stock_alerts');
    }
};
