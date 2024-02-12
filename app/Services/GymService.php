<?php

namespace App\Services;

use App\Exceptions\Gym\UserHasReachedMaximumNumberOfGymsException;
use App\Jobs\Gym\GymShutsDownJob;
use App\LogicValidators\Gym\UserRegisterGymNumberLogicValidator;
use App\Models\BaseModel;
use App\Models\Gym;
use App\Models\Membership;
use App\Models\SocialMediaLinks;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class GymService extends BaseService
{
    /**
     * @param Gym $model
     * @param FileService $fileService
     */
    public function __construct(Gym $model, public FileService $fileService)
    {
        parent::__construct($model);
    }

    /**
     * @param array $data
     * @return BaseModel|User
     * @throws Exception
     */
    public function store(array $data): BaseModel|User
    {
        $data = $this->resolveUserId($data);

        $this->validateCreate();

        $gym = $this->model->create($data);

        $this->storeGymImages($data, $gym->getKey());
        $this->createOrUpdateSocialMediaLinks($data, $gym);

        return $this->show($gym);
    }

    /**
     * @return void
     * @throws UserHasReachedMaximumNumberOfGymsException
     */
    public function validateCreate(): void
    {
        (new UserRegisterGymNumberLogicValidator())->validate();
    }

    /**
     * @param BaseModel|User $gym
     * @param array $data
     * @return Builder|array|Collection|Model|Builder[]
     */
    public function update(BaseModel|User $gym, array $data): Builder|array|Collection|Model
    {
        $data = $this->shutDownGym($data, $gym);

        $gym->update($data);

        $this->storeGymImages($data, $gym->getKey());
        $this->createOrUpdateSocialMediaLinks($data, $gym);

        return $this->show($gym);
    }

    /**
     * @param array $data
     * @param Gym $gym
     * @return void
     */
    public function createOrUpdateSocialMediaLinks(array $data, Gym $gym): void
    {
        if (!Arr::hasAny($data,
            [
                'instagram_link',
                'facebook_link',
                'twitter_link',
            ]
        )) {
            return;
        }

        $instagramLink = $data['instagram_link'] ?? '';
        $facebookLink = $data['facebook_link'] ?? '';
        $twitterLink = $data['twitter_link'] ?? '';

        SocialMediaLinks::query()->updateOrCreate([
            'gym_id' => $gym->getKey(),
        ],
            [
                'instagram_link' => $instagramLink,
                'facebook_link' => $facebookLink,
                'twitter_link' => $twitterLink,
            ]);
    }

    /**
     * @param array $data
     * @param int $gymId
     * @return void
     */
    public function storeGymImages(array $data, int $gymId): void
    {
        if (Arr::has($data, 'cover_image')) {
            $this->fileService->storeGymImage($data['cover_image'], $gymId, true);
        }

        if (Arr::has($data, 'images')) {
            foreach ($data['images'] as $image) {
                $this->fileService->storeGymImage($image, $gymId);
            }
        }
    }

    /**
     * @param array $data
     * @return array
     */
    private function resolveUserId(array $data): array
    {
        if (Arr::has($data, 'owner_id')) {
            return $data;
        }

        $data['owner_id'] = Auth::id();

        return $data;
    }

    /**
     * @param array $data
     * @param Gym $gym
     * @return array
     */
    public function shutDownGym(array $data, Gym $gym): array
    {
        if(!Arr::has($data, 'shutdown_date')){
            return $data;
        }elseif(Arr::has($data, 'shutdown_date') && $gym->shutdown_date){
            unset($data['shutdown_date']);
            return $data;
        }

        $shutdownDate = Carbon::parse($data['shutdown_date']);

        GymShutsDownJob::dispatch($gym, $shutdownDate);

        return $data;
    }
}
