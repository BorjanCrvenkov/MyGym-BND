<?php

namespace Database\Seeders;

use App\Models\Gym;
use App\Models\Membership;
use App\Models\MembershipType;
use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class UserSeeder extends Seeder
{
    /**
     * @param ExpenseTypeSeeder $expenseTypeSeeder
     * @param WorkingTimeSeeder $workingTimeSeeder
     */
    public function __construct(public ExpenseTypeSeeder $expenseTypeSeeder, public WorkingTimeSeeder $workingTimeSeeder)
    {
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->administrator()->create([
            'email' => 'admin@email.com'
        ]);

        $business = User::factory()->business()->create([
            'email' => 'business@email.com'
        ]);

        $gym = Gym::factory()->create([
            'owner_id' => $business->getKey(),
        ]);

        $gymSeeder = App::make(GymSeeder::class);

        $gymSeeder->seedGymEmployees($gym);
        $gymSeeder->seedGymEquipment($gym);
        $gymSeeder->seedGymMembershipTypes($gym);
        $gymSeeder->seedCoverImage($gym);
        $gymSeeder->seedGymImages($gym);
        $this->seedMembers($gym->getKey());

        User::factory()->employee()->create([
            'email'  => 'employee@email.com',
            'gym_id' => $gym->getKey(),
        ]);

        User::factory()->create([
            'email' => 'member@email.com',
        ]);
    }

    /**
     * @param Gym $gym
     * @param int $count
     * @return void
     * @throws Exception
     */
    public function seedEmployees(Gym $gym, int $count = 5): void
    {
        $employees = User::factory($count)->employee()->create([
            'gym_id' => $gym->getKey(),
        ]);

        foreach ($employees as $employee){
            $this->expenseTypeSeeder->seedEmployeeExpenseType($employee);
            $this->workingTimeSeeder->run($employee);
        }
    }

    /**
     * @param int|null $gymId
     * @return void
     * @throws Exception
     */
    public function seedMembers(?int $gymId = null): void
    {
        $randomMemberNumber = random_int(10, 50);

        $members = User::factory($randomMemberNumber)->create();

        $membershipSeeder = App::make(MembershipSeeder::class);

        foreach ($members as $member){
            $membershipType = MembershipType::query()
                ->where('gym_id', '!=', $gymId ?? 1)
                ->inRandomOrder()->first();

            if($membershipType){
                $membershipSeeder->run($membershipType, $member);
            }

            if($gymId){
                $membershipType = MembershipType::query()->where('gym_id', '=', $gymId)->inRandomOrder()->first();

                $membershipSeeder->run($membershipType, $member);

                App::make(ReviewSeeder::class)->run($member->getKey(), $gymId);
            }
        }
    }
}
