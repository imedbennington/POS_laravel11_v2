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
        Schema::table('settings', function (Blueprint $table) {
            // Change the 'value' column type to integer
            $table->integer('value')->change();

            // Add the 'description' column
            $table->string('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // Revert the 'value' column back to string
            $table->string('value')->change();

            // Drop the 'description' column
            $table->dropColumn('description');
        });
    }
};
