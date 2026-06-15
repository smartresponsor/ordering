<?php

declare(strict_types=1);

namespace App\EventListener\Http;

use App\Factory\Http\OrderProblemFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final readonly class OrderProblemExceptionListener
{
    public function __construct(private OrderProblemFactory $problemFactory)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        if ($throwable instanceof ValidationFailedException) {
            $event->setResponse($this->problemFactory->create(
                'Validation failed',
                $throwable->getMessage(),
                Response::HTTP_UNPROCESSABLE_ENTITY,
            ));

            return;
        }

        $status = $throwable instanceof HttpExceptionInterface ? $throwable->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
        $title = $status >= 500 ? 'Internal Server Error' : 'Request failed';
        $event->setResponse($this->problemFactory->create($title, $throwable->getMessage(), $status));
    }
}
