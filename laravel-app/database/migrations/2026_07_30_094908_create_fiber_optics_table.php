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
        Schema::create('fiber_optics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained('cities')->cascadeOnDelete();
            $table->string('type')->nullable();
            $table->unsignedInteger('count')->default(0);
            $table->string('location')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fiber_optics');
    }
};
