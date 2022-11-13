<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponseService extends JsonResponseService
{
    public function getResponse($data, int $statusCode = 200): JsonResponse
    {
        return $this->get(['data' => $data], $statusCode);
    }

    public function getErrors($messages, int $statusCode = 200): JsonResponse
    {
        return $this->get(['errors' => $messages], $statusCode);
    }
}
