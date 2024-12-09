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
        Schema::table('food_drinks', function (Blueprint $table) {
            $table->decimal('unit_price', 10, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('food_drinks', function (Blueprint $table) {
            $table->decimal('unit_price', 8, 2)->change();
        });
    }
};
