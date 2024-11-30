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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('provider_id')
                ->after('product_id') // Adds the column after 'product_id'
                ->constrained('providers') // References the 'id' column in the 'providers' table
                ->onDelete('cascade'); // Deletes orders if the provider is deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['provider_id']); // Drop foreign key constraint
            $table->dropColumn('provider_id');    // Remove the column
        });
    }
};
