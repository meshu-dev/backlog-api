<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Service\JsonResponseService;

class ApiController extends AbstractController
{
    public function __construct(
        protected JsonResponseService $jsonResponseService
    ) { }

    public function getRequestParams(Request $request)
    {
        $content = $request->getContent();

        $params = $request->request->all();
    
        if ($content == null && isset($params['body']) === true) {
            return $params['body'];
        }
    
        return json_decode($content, true);
    }

    public function getResponse($data, $statusCode = 200)
    {
        $data = ['data' => $data];
        return $this->jsonResponseService->get($data, $statusCode);
    }

    public function getErrorResponse($params, $statusCode = 400)
    {
        $params = ['errors' => $params];
        return $this->json($params, $statusCode);
    }
}
