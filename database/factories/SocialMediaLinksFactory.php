<?php

namespace Database\Factories;

use App\Models\Gym;
use App\Models\SocialMediaLinks;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SocialMediaLinks>
 */
class SocialMediaLinksFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'gym_id'         => Gym::factory(),
            'instagram_link' => $this->faker->url(),
            'facebook_link'  => $this->faker->url(),
            'twitter_link'   => $this->faker->url(),
        ];
    }
}
