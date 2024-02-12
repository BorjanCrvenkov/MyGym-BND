<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->json('original_membership_type_model')->nullable();
            $table->string('charge_id')->nullable();
            $table->boolean('refunded')->default(false);
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('membership_type_id')->references('id')->on('membership_types');
            $table->foreignId('gym_id')->nullable()->references('id')->on('gyms');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
