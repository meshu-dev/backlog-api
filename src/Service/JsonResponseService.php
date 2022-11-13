<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;

class JsonResponseService extends ResponseService
{
    public function get($data, int $statusCode = 200): JsonResponse
    {
        return $this->makeResponse($data, $statusCode);
    }

    protected function makeResponse($params, int $statusCode = 200): JsonResponse
    {
        $params = $this->serialiseService->convert($params, 'json');
        return JsonResponse::fromJsonString($params, $statusCode);
    }
}
