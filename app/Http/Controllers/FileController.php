<?php

namespace App\Http\Controllers;

use App\Enums\FileTypeEnum;
use App\Http\CustomResponse\CustomResponse;
use App\Http\Requests\StoreFileRequest;
use App\Http\Resources\FileCollection;
use App\Http\Resources\FileResource;
use App\Models\File as FileModel;
use App\Services\FileService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class FileController extends Controller
{
    final public const IDENTIFICATION_FILES_DESTINATION_PATH = '/uploads/identificationFiles/';
    final public const IMAGE_DESTINATION_PATH = '/uploads/userImages/';

    final public const GYM_IMAGE_DESTINATION_PATH = '/uploads/gymImages/';

    /**
     * @param FileModel $model
     * @param FileService $service
     * @param CustomResponse $response
     */
    public function __construct(FileModel $model, FileService $service, CustomResponse $response)
    {
        parent::__construct($model, $service, $response, FileResource::class, FileCollection::class, 'file');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return $this->indexHelper();
    }

    /**
     * @param StoreFileRequest $request
     * @return JsonResponse
     */
    public function store(StoreFileRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $file = $validatedData['file'];
        $fileType = $validatedData['file_type'];
        $modelId = $validatedData['model_id'];

        match ($fileType) {
            FileTypeEnum::GYM_IMAGE->value => $this->service->storeGymImage($file, $modelId),
            default => null,
        };

        return $this->response->success();
    }

    /**
     * @param FileModel $file
     * @return JsonResponse
     */
    public function destroy(FileModel $file): JsonResponse
    {
        return $this->destroyHelper($file);
    }

    /**
     * @param string $identificationFileName
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function returnIdentificationFiles(string $identificationFileName)
    {
        $path = base_path() . self::IDENTIFICATION_FILES_DESTINATION_PATH . $identificationFileName;

        try {
            $file = File::get($path);

            $fileModel = FileModel::query()->where('name', '=', $identificationFileName)->first();

            $mime = $fileModel->mime;

            $response = Response::make($file);
            $response->header('Content-Type', $mime);

            return $response;
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }
    }

    /**
     * @param string $imageName
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function returnUserImage(string $imageName)
    {
        $path = base_path() . self::IMAGE_DESTINATION_PATH . $imageName;

        try {
            $file = File::get($path);

            $fileModel = FileModel::query()->where('name', '=', $imageName)->first();

            $mime = $fileModel->mime;

            $response = Response::make($file);
            $response->header('Content-Type', $mime);

            return $response;
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }
    }


    /**
     * @param string $imageName
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function returnGymImage(string $imageName)
    {
        $path = base_path() . self::GYM_IMAGE_DESTINATION_PATH . $imageName;

        try {
            $file = File::get($path);

            $fileModel = FileModel::query()->where('name', '=', $imageName)->first();

            $mime = $fileModel->mime;

            $response = Response::make($file);
            $response->header('Content-Type', $mime);

            return $response;
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }
    }
}
