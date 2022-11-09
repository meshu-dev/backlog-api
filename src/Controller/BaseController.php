<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\SerializeService;

class BaseController extends AbstractController
{
    public function __construct(protected SerializeService $serializeService) { }

    public function getRequestJson(Request $request)
    {
        $content = $request->getContent();
        return json_decode($content, true);
    }

    public function getJson($entities, int $statusCode = 200): JsonResponse
    {
        $params = ['data' => $entities];
        $serializedEntities = $this->serializeService->convert($params, 'json');
        
        return JsonResponse::fromJsonString($serializedEntities, $statusCode);
    }
}
