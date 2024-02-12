<?php

namespace Database\Seeders;

use App\Enums\UserRolesEnum;
use App\Jobs\Gym\CalculateGymRatingJob;
use App\Models\Gym;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class GymSeeder extends Seeder
{
    /**
     * @param UserSeeder $userSeeder
     * @param EquipmentSeeder $equipmentSeeder
     * @param MembershipTypeSeeder $membershipTypeSeeder
     * @param ReportSeeder $reportSeeder
     * @param FileSeeder $fileSeeder
     */
    public function __construct(
        public UserSeeder $userSeeder,
        public EquipmentSeeder $equipmentSeeder,
        public MembershipTypeSeeder $membershipTypeSeeder,
        public ReportSeeder $reportSeeder,
        public FileSeeder $fileSeeder,
    )
    {
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gyms = Gym::factory(10)->create();

        foreach ($gyms as $gym){
            $this->seedGymEmployees($gym);
            $this->seedGymEquipment($gym);
            $this->seedGymMembershipTypes($gym);
            $this->reportSeeder->run($gym);
            $this->seedGymReviews($gym);
            $this->seedCoverImage($gym);
            $this->seedGymImages($gym);
            CalculateGymRatingJob::dispatch($gym->getKey());
        }
    }

    /**
     * @param Gym $gym
     * @return void
     * @throws Exception
     */
    public function seedGymEmployees(Gym $gym): void
    {
        $this->userSeeder->seedEmployees($gym, random_int(2, 10));

    }

    /**
     * @param Gym $gym
     * @return void
     * @throws Exception
     */
    public function seedGymEquipment(Gym $gym): void
    {
        $this->equipmentSeeder->run($gym, random_int(5, 15));

    }

    /**
     * @param Gym $gym
     * @return void
     */
    public function seedGymMembershipTypes(Gym $gym): void
    {
        $this->membershipTypeSeeder->run($gym);
    }

    /**
     * @param Gym $gym
     * @return void
     * @throws Exception
     */
    public function seedGymReviews(Gym $gym): void
    {
        $numberOfReviewsToBeSeeded = random_int(4,30);

        $reviewSeeder = App::make(ReviewSeeder::class);

        for ($i = 0; $i < $numberOfReviewsToBeSeeded; $i++){
            $memberRoleId = Role::query()->where('name', '=', UserRolesEnum::MEMBER->value)->first()->getKey();

            $userId = User::query()
                ->where('role_id', '=', $memberRoleId)
                ->inRandomOrder()->first()->getKey();

            $reviewSeeder->run($userId, $gym->getKey());
        }
    }

    /**
     * @param Gym $gym
     * @return void
     * @throws Exception
     */
    public function seedCoverImage(Gym $gym): void
    {
        $this->fileSeeder->createGymCoverImage($gym);
    }

    /**
     * @param Gym $gym
     * @return void
     */
    public function seedGymImages(Gym $gym): void
    {
        $this->fileSeeder->createGymImages($gym);
    }
}
