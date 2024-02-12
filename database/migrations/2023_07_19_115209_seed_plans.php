<?php

use App\Models\Plan;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Plan::factory()->create([
            'name'                        => 'Standard - Monthly',
            'stripe_plan'                 => 'price_1NY6OJGaXOwp86oKpix1kK1j',
            'price'                       => 100,
            'number_of_allowed_gyms'      => 1,
            'number_of_allowed_employees' => 2,
            'duration_months'             => 1,
        ]);

        Plan::factory()->create([
            'name'                        => 'Gold - Monthly',
            'stripe_plan'                 => 'price_1NY6OgGaXOwp86oKaIn6T0bz',
            'price'                       => 200,
            'number_of_allowed_gyms'      => 3,
            'number_of_allowed_employees' => 6,
            'duration_months'             => 1,
        ]);

        Plan::factory()->create([
            'name'                        => 'Premium - Monthly',
            'stripe_plan'                 => 'price_1NY6P0GaXOwp86oKVGlco5MY',
            'price'                       => 300,
            'number_of_allowed_gyms'      => 10,
            'number_of_allowed_employees' => 50,
            'duration_months'             => 1,
        ]);

        Plan::factory()->create([
            'name'                        => 'Standard - Yearly',
            'stripe_plan'                 => 'price_1NY6PJGaXOwp86oK3K6ZbZeU',
            'price'                       => 1100,
            'number_of_allowed_gyms'      => 1,
            'number_of_allowed_employees' => 2,
            'duration_months'             => 12,
        ]);

        Plan::factory()->create([
            'name'                        => 'Gold - Yearly',
            'stripe_plan'                 => 'price_1NY6PfGaXOwp86oKuyUEPkfT',
            'price'                       => 2100,
            'number_of_allowed_gyms'      => 3,
            'number_of_allowed_employees' => 6,
            'duration_months'             => 12,
        ]);

        Plan::factory()->create([
            'name'                        => 'Premium - Yearly',
            'stripe_plan'                 => 'price_1NY6PrGaXOwp86oKdhjaGIje',
            'price'                       => 3100,
            'number_of_allowed_gyms'      => 10,
            'number_of_allowed_employees' => 50,
            'duration_months'             => 12,
        ]);
    }
};
