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
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->nullable(); // safety, comfort, technology, etc.
            $table->timestamps();
        });

        // Pivot table for car_listing_feature
        Schema::create('car_listing_feature', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_listing_id')->constrained()->onDelete('cascade');
            $table->foreignId('feature_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['car_listing_id', 'feature_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_listing_feature');
        Schema::dropIfExists('features');
    }
};
