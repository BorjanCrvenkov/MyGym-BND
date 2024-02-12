<?php

namespace App\Services;

use App\Models\BaseModel;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class EquipmentService extends BaseService
{
    /**
     * @param Equipment $model
     * @param FileService $fileService
     */
    public function __construct(Equipment $model, public FileService $fileService)
    {
        parent::__construct($model);
    }

    /**
     * @param array $data
     * @return BaseModel|User
     */
    public function store(array $data): BaseModel|User
    {
        $equipment = $this->model->create($data);

        $this->fileService->storeEquipmentImage($data['image'], $equipment);

        return $this->show($equipment);
    }

    /**
     * @param BaseModel|User $equipment
     * @param array $data
     * @return Builder|array|Collection|Model|Builder[]
     */
    public function update(BaseModel|User $equipment, array $data): Builder|array|Collection|Model
    {
        $equipment->update($data);

        $image = $data['image'] ?? null;
        $this->fileService->storeEquipmentImage($image, $equipment);

        return $this->show($equipment);
    }
}
