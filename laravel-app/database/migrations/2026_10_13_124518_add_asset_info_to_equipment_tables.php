<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = ['stations', 'antennas', 'devices', 'centrals'];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->string('status')->nullable();         // فارغ افتراضياً
                $t->string('serial_number')->nullable();  // فارغ افتراضياً
                $t->string('image')->nullable();          // مسار الصورة، فارغ افتراضياً
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn(['status', 'serial_number', 'image']);
            });
        }
    }
};
