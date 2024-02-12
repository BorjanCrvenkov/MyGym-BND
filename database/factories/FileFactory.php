<?php

namespace Database\Factories;

use App\Enums\FileTypeEnum;
use App\Models\Equipment;
use App\Models\File;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'        => $this->faker->word() . $this->faker->numberBetween(0, 10000000),
            'link'        => $this->faker->imageUrl(),
            'mime'        => $this->faker->mimeType(),
            'model_id'    => User::factory(),
            'file_type'   => FileTypeEnum::USER_IMAGE->value,
        ];
    }

    /**
     * @return $this
     */
    public function userIdentificationFile(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'model_id'    => User::factory(),
                'file_type'   => FileTypeEnum::USER_IDENTIFICATION_DOCUMENT->value,
            ];
        });
    }

    /**
     * @return $this
     */
    public function gymCoverImage(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'model_id'    => Gym::factory(),
                'file_type'   => FileTypeEnum::GYM_COVER_IMAGE->value,
            ];
        });
    }

    /**
     * @return $this
     */
    public function gymSideImage(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'model_id'    => Gym::factory(),
                'file_type'   => FileTypeEnum::GYM_IMAGE->value,
            ];
        });
    }

    /**
     * @return $this
     */
    public function equipmentImage(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'model_id'    => Equipment::factory(),
                'file_type'   => FileTypeEnum::EQUIPMENT_IMAGE->value,
            ];
        });
    }
}
