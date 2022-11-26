<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use App\Service\ApiResponseService;
use App\Exception\ValidationException;

class ExceptionListener
{
    public function __construct(protected ApiResponseService $apiResponseService) { }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ValidationException) {
            $error = $exception->getMessages();
        } else {
            throw $exception;
            $error = $exception->getMessage();
        }

        $response = $this->apiResponseService->getErrors(
            $error,
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
        
        $event->getResponse()->setContent($response);
    }
}
