<?php

namespace Database\Factories;

use App\Enums\ReviewTypeEnum;
use App\Enums\UserRolesEnum;
use App\Models\Gym;
use App\Models\Review;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    public static array $oneStarReviewText = [
        "This gym is a nightmare. The equipment is rusty and outdated. The trainers are nowhere to be found, and the ones
        who are present seem more interested in socializing than helping. Save your money",
        "This gym is a disaster waiting to happen. The lack of proper maintenance is shocking.
        I've seen broken machines and torn mats left untouched for weeks. It's a safety hazard.",
        "This gym is a joke. The classes are a waste of time – there's no structure or guidance. The trainers are clueless, and the overall vibe is uninspiring. Don't bother.",
        "This gym is a rip-off. The prices are astronomical, and the services are underwhelming. The so-called 'wellness' aspect is a facade to charge more. Find a real gym instead.",
        "This gym is a disappointment. The gym is a mess, and the trainers have no idea how to properly assist with lifting techniques. I've witnessed accidents waiting to happen.",
    ];

    public static array $twoStarReviewText = [
        "This gym has potential, but it falls short. The equipment is decent, but half of it is often out of order.
        The trainers are hit or miss – some are helpful, others just stand around.",
        "This gym needs improvement. The classes are too crowded to be effective, and the trainers don't pay much attention to form. T
        he gym itself is clean, but the experience is lackluster.",
        "This gym is average at best. The classes lack variety, and the trainers don't seem very passionate.
        The gym could benefit from some updates to equipment and ambiance.",
        "This gym is overpriced for what you get. The gym is fine, but the extra 'wellness' services are nothing special.
        I expected more for the premium cost.",
        "This gym has its flaws. The equipment is good, but the gym can get crowded during peak hours.
        The trainers are knowledgeable, but they're not always available when you need assistance.",
    ];

    public static array $threeStarReviewText = [
        "This gym is decent overall. The equipment is functional, but the layout is a bit cramped. The trainers are friendly,
        but their availability can be inconsistent. It's an average gym experience",
        "This gym has its pros and cons. The classes are energetic, but they fill up quickly. The gym is clean, but the locker
        rooms could use more attention. It's a middle-of-the-road choice.",
        "This gym is okay. The variety of classes is decent, but the instructors vary in quality. The gym could use a refresh,
        and the atmosphere lacks excitement. It's neither great nor terrible.",
        "This gym offers a mixed experience. The gym equipment is good, but the wellness services are hit or miss. It's pricier
         than other options, but you do get some value.",
        "This gym has its merits. The strength training equipment is solid, but the gym's focus on bodybuilding might not suit
        everyone. The trainers are knowledgeable, though a bit intense.",
    ];

    public static array $fourStarReviewText = [
        "This gym is a solid choice. The gym has a good selection of equipment, and the trainers are attentive.
        The only downside is that it can get crowded during peak hours.",
        "This gym delivers a great workout. The high-intensity classes are challenging and effective. T
        he gym is clean, and the staff is friendly. Just a few more class time options would make it perfect.",
        "This gym offers a good mix of classes. The trainers are dedicated, and the atmosphere is comfortable.
        It could benefit from some facility updates, but overall, it's a pleasant place to work out.",
        "This gym provides a comprehensive fitness experience. The gym equipment is top-notch, and the additional wellness services
        are a nice touch. The price is steep, but you get quality.",
        "This gym is great for serious lifters. The gym caters well to strength training, and the trainers are experienced.
        The atmosphere is motivating, although it could use more variety in equipment.",
    ];

    public static array $fiveStarReviewText = [
        "This gym is hands down the best gym I've ever been to! The variety of equipment and classes keeps things exciting.
        The trainers are friendly and know their stuff. I've seen remarkable progress since joining.",
        "This gym is my fitness haven! The classes are intense and addictive, and the trainers push you to achieve your best.
        The gym is always clean, and the positive atmosphere keeps me coming back for more.",
        "This gym is fantastic! The range of classes suits all levels, and the trainers genuinely care about your success.
        The facility is clean and well-maintained. It's the perfect place to reach your fitness goals",
        "This gym is worth every penny! The gym equipment is state-of-the-art, and the wellness services add a luxurious touch.
        The trainers and staff go above and beyond to ensure a top-notch experience.",
        "This gym is a lifter's dream! The equipment is top quality, and the trainers are passionate about strength training.
        The gym has a welcoming atmosphere, and I've seen remarkable gains in my strength.",
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rating'      => $this->faker->numberBetween(0, 5),
            'body'        => $this->faker->realText(),
            'reviewer_id' => User::factory(),
            'model_id'    => Gym::factory(),
            'model_type'  => ReviewTypeEnum::GYM_REVIEW->value,
        ];
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function oneStar(): static
    {
        $randomReviewIndex =random_int(0,4);

        return $this->state(function (array $attributes) use ($randomReviewIndex){
            return [
                'rating' => 1,
                'body'   => self::$oneStarReviewText[$randomReviewIndex],
            ];
        });
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function twoStar(): static
    {
        $randomReviewIndex =random_int(0,4);

        return $this->state(function (array $attributes) use ($randomReviewIndex){
            return [
                'rating' => 2,
                'body'   => self::$twoStarReviewText[$randomReviewIndex],
            ];
        });
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function threeStar(): static
    {
        $randomReviewIndex =random_int(0,4);

        return $this->state(function (array $attributes) use ($randomReviewIndex){
            return [
                'rating' => 3,
                'body'   => self::$threeStarReviewText[$randomReviewIndex],
            ];
        });
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function fourStar(): static
    {
        $randomReviewIndex =random_int(0,4);

        return $this->state(function (array $attributes) use ($randomReviewIndex){
            return [
                'rating' => 4,
                'body'   => self::$fourStarReviewText[$randomReviewIndex],
            ];
        });
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function fiveStar(): static
    {
        $randomReviewIndex =random_int(0,4);

        return $this->state(function (array $attributes) use ($randomReviewIndex){
            return [
                'rating' => 5,
                'body'   => self::$fiveStarReviewText[$randomReviewIndex],
            ];
        });
    }
}
