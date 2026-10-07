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
        Schema::create('centrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained('cities')->cascadeOnDelete();
            $table->string('type')->nullable();
            $table->string('version')->nullable();
            $table->unsignedInteger('analog_count')->default(0);      // تماثلية
            $table->unsignedInteger('digital_count')->default(0);     // رقمية
            $table->unsignedInteger('direct_count')->default(0);      // مباشرة
            $table->unsignedInteger('ac_units')->default(0);          // مكيفات
            $table->unsignedInteger('batteries')->default(0);         // بطاريات
            $table->string('ups')->default(0);                // UPS
            $table->unsignedInteger('external_capacity')->default(0); // سعة خارجية
            $table->unsignedInteger('internal_capacity')->default(0); // سعة داخلية
            $table->unsignedInteger('central_capacity')->default(0);  // سعة السنترال
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centrals');
    }
};
