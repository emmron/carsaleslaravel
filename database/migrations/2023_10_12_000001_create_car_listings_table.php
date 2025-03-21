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
        Schema::create('car_listings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->integer('year');
            $table->string('make');
            $table->string('model');
            $table->string('trim')->nullable();
            $table->string('body_type');
            $table->string('fuel_type');
            $table->string('transmission');
            $table->integer('odometer');
            $table->string('color');
            $table->string('vin')->nullable();
            $table->string('registration_number')->nullable();
            $table->date('registration_expiry')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('available'); // available, sold, pending
            $table->boolean('featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_listings');
    }
};