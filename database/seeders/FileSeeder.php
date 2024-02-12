<?php

namespace Database\Seeders;

use App\Enums\FileTypeEnum;
use App\Models\Equipment;
use App\Models\File;
use App\Models\Gym;
use Exception;
use Illuminate\Database\Seeder;

class FileSeeder extends Seeder
{
    public static array $gymCoverFiles = [
        [
            'name' => '1692693993_gym1.jpg',
            'link' => 'http://localhost/uploads/gymImages/1692693993_gym1.jpg',
            'mime' => 'image/jpeg',
        ],
        [
            'name' => '1692694001_gym2.jpg',
            'link' => 'http://localhost/uploads/gymImages/1692694001_gym2.jpg',
            'mime' => 'image/jpeg',
        ],
        [
            'name' => '1692694006_gym3.jpg',
            'link' => 'http://localhost/uploads/gymImages/1692694006_gym3.jpg',
            'mime' => 'image/jpeg',
        ],
        [
            'name' => '1692694010_gym4.jpeg',
            'link' => 'http://localhost/uploads/gymImages/1692694010_gym4.jpeg',
            'mime' => 'image/jpeg',
        ],
        [
            'name' => '1692694014_gym5.jpeg',
            'link' => 'http://localhost/uploads/gymImages/1692694014_gym5.jpeg',
            'mime' => 'image/jpeg',
        ],
        [
            'name' => '1692694020_gym6.jpeg',
            'link' => 'http://localhost/uploads/gymImages/1692694020_gym6.jpeg',
            'mime' => 'image/jpeg',
        ],
    ];

    public static array $equipmentFiles = [
        [
            'equipment_name'        => 'Treadmill',
            'equipment_description' => "A treadmill is a cardio exercise machine consisting of a moving platform on which users walk
            , jog, or run in place. It allows individuals to engage in indoor aerobic workouts by adjusting speed, incline,
            and workout programs according to their fitness goals.",
            'file_name'             => '1692701993_TREADMILL.png',
            'link'                  => 'http://localhost/uploads/userImages/1692701993_TREADMILL.png',
            'mime'                  => 'image/png',
        ],
        [
            'equipment_name'        => 'Climber',
            'equipment_description' => "A climber, also known as a stair climber or stair stepper, is a fitness machine designed
            to simulate the action of climbing stairs. Users step on two pedals that move up and down, providing a low-impact
             cardiovascular workout while engaging leg muscles and glutes. Some climbers also offer adjustable resistance levels and additional features
              for a more customizable workout experience.",
            'file_name'             => '1692702131_CLIMBER.png',
            'link'                  => 'http://localhost/uploads/userImages/1692702131_CLIMBER.png',
            'mime'                  => 'image/png',
        ],
        [
            'equipment_name'        => 'Recumbent bike',
            'equipment_description' => "A recumbent bike is a stationary exercise bicycle designed with a comfortable, reclined seat and a backrest.
            Users sit in a relaxed position with their legs extended forward to pedal. This type of bike offers a low-impact cardiovascular workout that is
            gentle on the joints, making it suitable for individuals of various fitness levels, especially those seeking a comfortable and
             supportive option for indoor cycling.",
            'file_name'             => '1692702183_RECUMBENTBIKE.png',
            'link'                  => 'http://localhost/uploads/userImages/1692702183_RECUMBENTBIKE.png',
            'mime'                  => 'image/png',
        ],
        [
            'equipment_name'        => 'Elliptical cross trainer',
            'equipment_description' => "An elliptical cross trainer is a fitness machine that provides a full-body, low-impact cardiovascular workout.
            Users stand on pedals and hold onto moving handlebars while performing smooth, elliptical-like motions that mimic walking, jogging,
            or running. This machine engages both upper and lower body muscles, making it an effective option for a comprehensive workout while
            reducing strain on joints. Adjustable resistance levels and workout programs offer versatility to accommodate different fitness goals.",
            'file_name'             => '1692702214_ELLIPTICALCROSS-TRAINER.png',
            'link'                  => 'http://localhost/uploads/userImages/1692702214_ELLIPTICALCROSS-TRAINER.png',
            'mime'                  => 'image/png',
        ],
        [
            'equipment_name'        => 'Incline bench',
            'equipment_description' => "An incline bench, also known as an incline weight bench, is a workout equipment designed with an adjustable
            backrest that can be inclined at various angles, typically between 15 to 45 degrees. It is commonly used in strength training
            and weightlifting routines. ",
            'file_name'             => '1692702253_INCLINEBENCH.png',
            'link'                  => 'http://localhost/uploads/userImages/1692702253_INCLINEBENCH.png',
            'mime'                  => 'image/png',
        ],
        [
            'equipment_name'        => 'Seated arm curl',
            'equipment_description' => "A seated arm curl machine is a gym apparatus that allows users to isolate and work their bicep muscles
            by sitting and curling their forearms against resistance. It helps in maintaining proper form while targeting bicep strength and development.",
            'file_name'             => '1692702298_SEATEDARMCURL.png',
            'link'                  => 'http://localhost/uploads/userImages/1692702298_SEATEDARMCURL.png',
            'mime'                  => 'image/png',
        ],
        [
            'equipment_name'        => 'Military bench',
            'equipment_description' => "A military bench, often called a military press bench, is designed for the military press exercise,
             involving lifting weights overhead while sitting or standing. It focuses on shoulder and triceps muscles and lacks a backrest
             for proper execution.",
            'file_name'             => '1692702344_MILITARYBENCH.png',
            'link'                  => 'http://localhost/uploads/userImages/1692702344_MILITARYBENCH.png',
            'mime'                  => 'image/png',
        ],
        [
            'equipment_name'        => 'Rear deltoid',
            'equipment_description' => "A rear deltoid machine is a piece of gym equipment specifically designed to target the rear deltoid muscles,
             which are located on the back of the shoulders. Users typically sit facing down and grasp handles, then perform a pulling motion
             to contract the rear deltoid muscles, contributing to shoulder strength and definition.",
            'file_name'             => '1692702377_FLY-REARDELTOID.jpg',
            'link'                  => 'http://localhost/uploads/userImages/1692702377_FLY-REARDELTOID.jpg',
            'mime'                  => 'image/jpeg',
        ],
        [
            'equipment_name'        => 'Shoulder press',
            'equipment_description' => "A shoulder press machine is a gym equipment designed for targeting the shoulder muscles.
             Users sit on the machine's adjustable seat, grasp the handles, and push upward to extend their arms while engaging the shoulders and triceps,
             then lower the handles back down in a controlled motion.",
            'file_name'             => '1692702401_SHOULDERPRESS.jpg',
            'link'                  => 'http://localhost/uploads/userImages/1692702401_SHOULDERPRESS.jpg',
            'mime'                  => 'image/jpeg',
        ],
        [
            'equipment_name'        => 'Chin, dip, leg raise',
            'equipment_description' => "A chin, dip, leg raise machine is a versatile gym equipment that allows users to perform multiple exercises.
            It typically consists of bars for chin-ups and dips, along with padded arm and back supports for leg raises,
            providing a comprehensive workout for the upper body and core muscles.",
            'file_name'             => '1692702426_CHIN-DIP-LEGRAISE.png',
            'link'                  => 'http://localhost/uploads/userImages/1692702426_CHIN-DIP-LEGRAISE.png',
            'mime'                  => 'image/png',
        ],
        [
            'equipment_name'        => 'Leg press',
            'equipment_description' => "A leg press machine is a weight training device designed to work the lower body muscles,
            especially the quadriceps, hamstrings, and glutes. Users typically sit on a sled or platform and use their legs to push
            the weight upward, simulating the motion of a squat without placing stress on the back.",
            'file_name'             => '1692702454_LEGPRESS.png',
            'link'                  => 'http://localhost/uploads/userImages/1692702454_LEGPRESS.png',
            'mime'                  => 'image/png',
        ],
        [
            'equipment_name'        => 'Lat pull down',
            'equipment_description' => "A lat pull-down machine is a gym equipment designed for targeting the back muscles.
            Users sit and grasp a bar attached to a cable, then pull the bar down towards the chest, engaging the upper back and arms in a
            controlled pulling motion.",
            'file_name'             => '1692702489_LATPULLDOWN.jpg',
            'link'                  => 'http://localhost/uploads/userImages/1692702489_LATPULLDOWN.jpg',
            'mime'                  => 'image/jpeg',
        ],
        [
            'equipment_name'        => 'Smith machine',
            'equipment_description' => "A Smith machine is a weightlifting apparatus that consists of a guided barbell on vertical rails,
            providing a controlled range of motion for various exercises. It's often used for exercises like squats, bench presses, and overhead
             presses, offering stability and safety for lifters while allowing them to perform compound movements.",
            'file_name'             => '1692702522_SMITHMACHINE.png',
            'link'                  => 'http://localhost/uploads/userImages/1692702522_SMITHMACHINE.png',
            'mime'                  => 'image/png',
        ],
        [
            'equipment_name'        => 'Dumbbell rack',
            'equipment_description' => "Ensures easy access, safety, and organization of different dumbbell weights, promoting an efficient workout environment.",
            'file_name'             => '1692702541_DUMBBELLRACKS1.jpg',
            'link'                  => 'http://localhost/uploads/userImages/1692702541_DUMBBELLRACKS1.jpg',
            'mime'                  => 'image/jpeg',
        ],
        [
            'equipment_name'        => 'Jump rope',
            'equipment_description' => "",
            'file_name'             => '1692702568_JUMPROPE.jpg',
            'link'                  => 'http://localhost/uploads/userImages/1692702568_JUMPROPE.jpg',
            'mime'                  => 'image/jpeg',
        ],
        [
            'equipment_name'        => 'Bench press',
            'equipment_description' => "The bench press is a fundamental weightlifting exercise performed lying on a bench, where a barbell is
            lowered and then pushed upward from the chest using the pectoral muscles, shoulders, and triceps. It's a common strength training
            exercise that targets the upper body muscles while also engaging the core for stability.",
            'file_name'             => '1692702608_Screenshotfrom2023-08-2212-56-35.png',
            'link'                  => 'http://localhost/uploads/userImages/1692702608_Screenshotfrom2023-08-2212-56-35.png',
            'mime'                  => 'image/png',
        ],
    ];

    public static array $gymImageFiles = [
        [
            'name' => '1692706141_GymInterior1.jpg',
            'link' => 'http://localhost/uploads/gymImages/1692706141_GymInterior1.jpg',
            'mime' => 'image/jpeg',
        ],
        [
            'name' => '1692706141_GymInterior5.jpg',
            'link' => 'http://localhost/uploads/gymImages/1692706141_GymInterior5.jpg',
            'mime' => 'image/jpeg',
        ],
        [
            'name' => '1692706141_GymInterior4.png',
            'link' => 'http://localhost/uploads/gymImages/1692706141_GymInterior4.png',
            'mime' => 'image/png',
        ],
        [
            'name' => '1692706141_GymInterior3.jpeg',
            'link' => 'http://localhost/uploads/gymImages/1692706141_GymInterior3.jpeg',
            'mime' => 'image/jpeg',
        ],
        [
            'name' => '1692706141_GymInterior2.jpg',
            'link' => 'http://localhost/uploads/gymImages/1692706141_GymInterior2.jpg',
            'mime' => 'image/jpeg',
        ],
    ];

    /**
     * @param Gym $gym
     * @return void
     * @throws Exception
     */
    public function createGymCoverImage(Gym $gym): void
    {
        $fileAttributes = self::$gymCoverFiles[random_int(0, 5)];

        File::factory()->create([
            'name'      => $fileAttributes['name'],
            'link'      => $fileAttributes['link'],
            'mime'      => $fileAttributes['mime'],
            'model_id'  => $gym->getKey(),
            'file_type' => FileTypeEnum::GYM_COVER_IMAGE->value,
        ]);
    }

    /**
     * @param Equipment $equipment
     * @return void
     * @throws Exception
     */
    public function createEquipmentImage(Equipment $equipment): void
    {
        $fileAttributes = self::$equipmentFiles[random_int(0, 15)];

        File::factory()->create([
            'name'      => $fileAttributes['file_name'],
            'link'      => $fileAttributes['link'],
            'mime'      => $fileAttributes['mime'],
            'model_id'  => $equipment->getKey(),
            'file_type' => FileTypeEnum::EQUIPMENT_IMAGE->value,
        ]);

        $equipment->update([
            'name'        => $fileAttributes['equipment_name'],
            'description' => $fileAttributes['equipment_description'],
        ]);
    }

    /**
     * @param Gym $gym
     * @return void
     */
    public function createGymImages(Gym $gym): void
    {
        foreach (self::$gymImageFiles as $imageFile){
            File::factory()->create([
                'name'      => $imageFile['name'],
                'link'      => $imageFile['link'],
                'mime'      => $imageFile['mime'],
                'model_id'  => $gym->getKey(),
                'file_type' => FileTypeEnum::GYM_IMAGE->value,
            ]);
        }
    }
}
