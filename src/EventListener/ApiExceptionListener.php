<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Exception\ApiExceptionInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ApiExceptionListener implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct()
    {
        $this->logger = new NullLogger();
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof ApiExceptionInterface) {
            return;
        }
        $this->logger->warning(
            'Error',
            ['message' => $exception->getMessage(), 'trace' => $exception->getTraceAsString()]
        );

        $response = new JsonResponse(
            [
                'code' => $exception->getCode(),
                'status' => 'Client side error',
                'message' => $exception->getMessage()
            ],
            $exception->getCode(),
            ['Content-Type' => 'application/problem+json']
        );

        $event->setResponse($response);
    }
}
