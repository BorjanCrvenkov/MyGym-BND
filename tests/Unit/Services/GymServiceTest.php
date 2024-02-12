<?php

namespace Tests\Unit\Services;

use App\Models\Gym;
use App\Models\SocialMediaLinks;
use App\Models\User;
use App\Services\GymService;
use Closure;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Tests\Unit\BaseTests\BaseServiceTest;


class GymServiceTest extends BaseServiceTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->service = App::make(GymService::class);
        $this->model = App::make(Gym::class);
        $this->shouldAssert = true;
    }

    /**
     * @param Closure|null $data
     * @return void
     * @dataProvider indexDataProvider
     */
    public function testIndex(Closure $data = null): void
    {
        [$user, $expectedIds] = $data();

        $this->be($user);

        $actualIds = $this->service->index()->modelKeys();

        $this->assertEqualsCanonicalizing($expectedIds, $actualIds);
    }

    /**
     * @return array[]
     */
    public static function indexDataProvider(): array
    {
        return [
            'Scenario 1: Logged in user is administrator' => [
                'data' => function () {
                    $user = User::factory()->administrator()->create();

                    $expectedIds = Gym::factory(2)->create()->modelKeys();

                    return [
                        $user,
                        $expectedIds,
                    ];
                },
            ],
            'Scenario 2: Logged in user is business'      => [
                'data' => function () {
                    $user = User::factory()->business()->create();

                    $expectedIds = Gym::factory(2)->create([
                        'owner_id' => $user->getKey(),
                    ])->modelKeys();

                    Gym::factory(2)->create();

                    return [
                        $user,
                        $expectedIds,
                    ];
                },
            ],
            'Scenario 3: Logged in user is member'        => [
                'data' => function () {
                    $user = User::factory()->create();

                    $expectedIds = Gym::factory(2)->create()->modelKeys();

                    return [
                        $user,
                        $expectedIds,
                    ];
                },
            ],
        ];
    }


    /**
     * @param Closure|null $data
     * @return void
     */
    public function testStore(Closure $data = null): void
    {
        Storage::fake();

        $createData = $this->createModel()->first()->toArray();

        $id = $createData['id'];

        unset($createData['id'], $createData['created_at'], $createData['updated_at'], $createData['social_media_links']);

        $image = UploadedFile::fake()->create('test_file.jpg', 100);
        $createData['cover_image'] = $image;

        $this->model->query()->find($id)->forceDelete();

        $createdModel = $this->service->store($createData);
        $createData['id'] = $createdModel->getKey();
        unset($createData['working_times'], $createData['cover_image']);

        $this->assertDatabaseHas($this->model->getTable(), $createData);
    }

    /**
     * @return void
     */
    public function testUpdate(): void
    {
        $updateData = $this->createModel()->first()->toArray();

        $id = $updateData['id'];

        unset($updateData['id'], $updateData['created_at'], $updateData['updated_at'], $updateData['cover_image'], $updateData['social_media_links']);

        $this->model->query()->find($id)->forceDelete();

        $model = $this->createModel()->first();

        $updatedModel = $this->service->update($model, $updateData);
        $updateData['id'] = $updatedModel->getKey();
        unset($updateData['working_times']);

        $this->assertDatabaseHas($this->model->getTable(), $updateData);
    }

    /**
     * @param Closure $data
     * @return void
     * @dataProvider createOrUpdateSocialMediaLinksDataProvider
     */
    public function testCreateOrUpdateSocialMediaLinks(Closure $data)
    {
        [$gym, $requestData] = $data();

        $this->service->createOrUpdateSocialMediaLinks($requestData, $gym);

        $this->assertDatabaseHas('social_media_links', [
            'gym_id' => $gym->getKey(),
            ...$requestData,
        ]);
    }

    /**
     * @return array[]
     */
    public static function createOrUpdateSocialMediaLinksDataProvider(): array
    {
        return [
            "Scenario 1: Social media model doesn't exist" => [
                'data' => function () {
                    $gym = Gym::factory()->create();
                    SocialMediaLinks::query()->where('gym_id','=', $gym->getKey())->forceDelete();

                    return [
                        $gym,
                        [
                            'instagram_link' => 'instagram_link_test',
                            'facebook_link'  => 'facebook_link_test',
                            'twitter_link'   => 'twitter_link_test',
                        ],
                    ];
                }
            ],
            "Scenario 2: Social media model exists"        => [
                'data' => function () {
                    $gym = Gym::factory()->create();

                    SocialMediaLinks::factory()->create([
                        'gym_id' => $gym->getKey()
                    ]);

                    return [
                        $gym,
                        [
                            'instagram_link' => 'instagram_link_test',
                            'facebook_link'  => 'facebook_link_test',
                            'twitter_link'   => 'twitter_link_test',
                        ],
                    ];
                }
            ],
        ];
    }
}
