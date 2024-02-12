<?php

namespace Database\Seeders;

use App\Models\Gym;
use App\Models\MembershipType;
use Illuminate\Database\Seeder;

class MembershipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Gym $gym): void
    {
        $membershipTypeNames = [
            'Silver',
            'Gold',
            'Platinum',
        ];

        foreach ($membershipTypeNames as $name) {
            if ($name == 'Silver') {
                $description = 'Our Silver membership offers access to our state-of-the-art facilities, including a wide range of cardio
                and strength equipment. Stay committed to your fitness journey with unlimited group classes and personalized
                assistance from our expert trainers.';
            } elseif ($name == ' Gold') {
                $description = "Elevate your fitness experience with our Gold membership, granting you all the benefits of Silver,
                plus exclusive access to premium amenities such as sauna, steam room, and priority booking for popular group classes.
                Enjoy a holistic approach to wellness that empowers you to reach your goals";
            } else {
                $description = 'Unleash the full potential of your fitness aspirations with our Platinum membership.
                In addition to the perks of Gold, relish in one-on-one sessions with our top-tier trainers, nutritional guidance,
                and complimentary sports massages. This all-inclusive package ensures an unparalleled fitness journey tailored to your individual needs.';
            }

            MembershipType::factory()->create([
                'name'        => $name,
                'description' => $description,
                'gym_id'      => $gym->getKey(),
            ]);
        }
    }
}
