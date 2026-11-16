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
        Schema::create('cameras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained('cities')->cascadeOnDelete();
            $table->string('item');                       // النوع
            $table->unsignedInteger('count')->default(1); // العدد
            $table->string('location')->nullable();       // الموقع
            $table->string('serial_number')->nullable();
            $table->string('status')->nullable();         // active / faulty / maintenance / retired
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cameras');
    }
};
