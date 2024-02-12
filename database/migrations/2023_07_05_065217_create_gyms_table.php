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
        Schema::create('gyms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->date('date_opened');
            $table->json('working_times');
            $table->string('phone_number')->nullable();
            $table->foreignId('owner_id')->references('id')->on('users');
            $table->float('rating')->default(0);
            $table->string('email')->nullable();
            $table->boolean('shutdown')->default(false);
            $table->date('shutdown_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gyms');
    }
};
