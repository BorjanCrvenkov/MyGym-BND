<?php

namespace App\Services;

use App\Enums\FileTypeEnum;
use App\Models\Equipment;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use App\Models\File as FileModel;

class FileService extends BaseService
{
    final public const IDENTIFICATION_FILES_DESTINATION_PATH = '/uploads/identificationFiles/';
    final public const USER_IMAGE_DESTINATION_PATH = '/uploads/userImages/';
    final public const GYM_IMAGE_DESTINATION_PATH = '/uploads/gymImages/';

    /**
     * @param FileModel $model
     */
    public function __construct(FileModel $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $data
     * @param int $userId
     * @param User|null $user
     * @return array
     */
    public function storeUserImage(array $data, int $userId, User $user = null): array
    {
        if (!Arr::has($data, 'image')) {
            return $data;
        }

        if ($user && $user->image) {
            $oldImageName = $user->image->name;

            $this->deleteImage($oldImageName);
        }

        $image = $data['image'];
        $imageName = time() . '_' . str_replace(' ', '', $image->getClientOriginalName());
        $mime = $image->getMimeType();

        $destinationPath = base_path(self::USER_IMAGE_DESTINATION_PATH);

        $image->move($destinationPath, $imageName);

        $appUrl = config('app.url');

        $link = $appUrl . self::USER_IMAGE_DESTINATION_PATH . $imageName;

        $createFileData = [
            'name'        => $imageName,
            'link'        => $link,
            'mime'        => $mime,
            'model_id'    => $userId,
            'file_type'   => FileTypeEnum::USER_IMAGE->value,
        ];

        $this->store($createFileData);

        return $data;
    }

    /**
     * @param array $data
     * @param int $userId
     * @param User|null $user
     * @return void
     */
    public function storeIdentificationFile(array $data, int $userId, User $user = null): void
    {
        if (!Arr::has($data, 'identification_file')) {
            return;
        }

        if ($user && $user->identification_file) {
            $oldIdentificationFileName = $user->identification_file->name;

            $this->deleteImage($oldIdentificationFileName, true);
        }

        $destinationPath = base_path(self::IDENTIFICATION_FILES_DESTINATION_PATH);

        $identificationFile = $data['identification_file'];
        $identificationFileName = time() . '_1_' . str_replace(' ', '', $identificationFile->getClientOriginalName());
        $mime = $identificationFile->getMimeType();

        $identificationFile->move($destinationPath, $identificationFileName);

        $appUrl = config('app.url');

        $link = $appUrl . self::IDENTIFICATION_FILES_DESTINATION_PATH . $identificationFileName;

        $createFileData = [
            'name'        => $identificationFileName,
            'link'        => $link,
            'mime'        => $mime,
            'model_id'    => $userId,
            'file_type'   => FileTypeEnum::USER_IDENTIFICATION_DOCUMENT->value,

        ];

        $this->store($createFileData);
    }

    /**
     * @param string $fileName
     * @param bool $identificationFile
     * @return void
     */
    public function deleteImage(string $fileName, bool $identificationFile = false): void
    {
        $destinationPath = base_path();
        $destinationPath .= $identificationFile ? self::IDENTIFICATION_FILES_DESTINATION_PATH : self::USER_IMAGE_DESTINATION_PATH;
        $destinationPath .= $fileName;

        FileModel::query()->where('name', '=', $fileName)->forceDelete();

        if (!File::exists($destinationPath)) {
            return;
        }

        File::delete($destinationPath);
    }

    /**
     * @param UploadedFile $file
     * @param int $gymId
     * @param bool $coverImage
     * @return void
     */
    public function storeGymImage(UploadedFile $file, int $gymId, bool $coverImage = false): void
    {
        $destinationPath = base_path(self::GYM_IMAGE_DESTINATION_PATH);

        $name = time() . '_' . str_replace(' ', '', $file->getClientOriginalName());
        $mime = $file->getMimeType();

        $file->move($destinationPath, $name);

        $appUrl = config('app.url');

        $link = $appUrl . self::GYM_IMAGE_DESTINATION_PATH . $name;

        $createFileData = [
            'name'        => $name,
            'link'        => $link,
            'mime'        => $mime,
            'model_id'    => $gymId,
            'file_type'   => $coverImage ? FileTypeEnum::GYM_COVER_IMAGE->value : FileTypeEnum::GYM_IMAGE->value,

        ];

        $this->store($createFileData);
    }

    /**
     * @param UploadedFile|null $file
     * @param Equipment|null $equipment
     * @return void
     */
    public function storeEquipmentImage(UploadedFile $file = null, Equipment $equipment = null): void
    {
        if(!$file){
            return;
        }

        if($equipment && $equipment->image){
            $this->deleteImage($equipment->image->name);
        }

        $imageName = time() . '_' . str_replace(' ', '', $file->getClientOriginalName());
        $mime = $file->getMimeType();

        $destinationPath = base_path(self::USER_IMAGE_DESTINATION_PATH);

        $file->move($destinationPath, $imageName);

        $appUrl = config('app.url');

        $link = $appUrl . self::USER_IMAGE_DESTINATION_PATH . $imageName;

        $createFileData = [
            'name'        => $imageName,
            'link'        => $link,
            'mime'        => $mime,
            'model_id'    => $equipment->getKey(),
            'file_type'   => FileTypeEnum::EQUIPMENT_IMAGE->value,
        ];

        $this->store($createFileData);
    }
}
