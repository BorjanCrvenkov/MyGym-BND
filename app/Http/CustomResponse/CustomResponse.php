<?php

namespace App\Http\CustomResponse;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Throwable;

class CustomResponse extends Response
{
    /**
     * @param int $code
     * @param string $message
     * @param array|JsonResource|ResourceCollection|null $data
     * @param array|null $auth
     * @return JsonResponse
     */
    public function getResponseStructure(int $code, string $message, array|JsonResource|ResourceCollection $data = null, array $auth = null): JsonResponse
    {
        $meta = [
            'code'    => $code,
            'message' => $message,
        ];

        return response()->json(array_filter([
            'meta' => $meta,
            'auth' => $auth,
            'data' => $data,
        ]), $code);
    }

    /**
     * @param string $message
     * @param array|JsonResource|ResourceCollection|null $data
     * @param array|null $auth
     * @return JsonResponse
     */
    public function success(string $message = 'Success', array|JsonResource|ResourceCollection $data = null, array $auth = null): JsonResponse
    {
        return $this->getResponseStructure(self::HTTP_OK, $message, $data, $auth);
    }

    /**
     * @param string $message
     * @param array|JsonResource|ResourceCollection|null $data
     * @param array|null $auth
     * @return JsonResponse
     */
    public function created(string $message = 'Created', array|JsonResource|ResourceCollection $data = null, array $auth = null): JsonResponse
    {
        return $this->getResponseStructure(self::HTTP_CREATED, $message, $data, $auth);
    }

    /**
     * @param string $message
     * @param array|JsonResource|ResourceCollection|null $data
     * @param array|null $auth
     * @return JsonResponse
     */
    public function updated(string $message = 'Updated', array|JsonResource|ResourceCollection $data = null, array $auth = null): JsonResponse
    {
        return $this->getResponseStructure(self::HTTP_OK, $message, $data, $auth);
    }

    /**
     * @return JsonResponse
     */
    public function noContent(): JsonResponse
    {
        return $this->getResponseStructure(self::HTTP_NO_CONTENT, 'No content');
    }

    /**
     * @return JsonResponse
     */
    public function unauthenticated(): JsonResponse
    {
        return $this->getResponseStructure(self::HTTP_UNAUTHORIZED, 'Unauthenticated');
    }

    /**
     * @param Exception|Throwable $exception
     * @return JsonResponse
     */
    public function unprocessableEntity(Exception|Throwable $exception): JsonResponse
    {
        $errorMessage = $this->resolveErrorMessage($exception);

        return $this->getResponseStructure(self::HTTP_UNPROCESSABLE_ENTITY, $errorMessage);
    }

    /**
     * @param Exception|Throwable $exception
     * @return string
     */
    public function resolveErrorMessage(Exception|Throwable $exception): string
    {
        $errors = $exception->errors();

        $message = '';

        foreach ($errors as $error) {
            $message .= $error[0];
        }

        return $message;
    }

    /**
     * @return JsonResponse
     */
    public function invalidLoginCredentials(): JsonResponse
    {
        return $this->getResponseStructure(self::HTTP_BAD_REQUEST, 'Invalid login credentials.');
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    public function badRequest(string $message): JsonResponse
    {
        return $this->getResponseStructure(self::HTTP_BAD_REQUEST, $message);

    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    public function notFound(string $message = 'Model not found'): JsonResponse
    {
        return $this->getResponseStructure(self::HTTP_NOT_FOUND, $message);
    }
}
