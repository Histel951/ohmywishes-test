<?php

declare(strict_types=1);

namespace App\Wishlist\Infrastructure\Http\EventSubscriber;

use App\Shared\Application\Exception\ValidationException;
use App\Wishlist\Domain\Exception\WishlistItemAlreadyExistsException;
use App\Wishlist\Domain\Exception\WishlistNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\ConstraintViolationInterface;

final class WishlistExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $response = match (true) {
            $exception instanceof ValidationException
            => $this->validationErrorResponse($exception),

            $exception instanceof WishlistNotFoundException
            => $this->errorResponse(
                message: $exception->getMessage(),
                statusCode: Response::HTTP_NOT_FOUND,
            ),

            $exception instanceof WishlistItemAlreadyExistsException
            => $this->errorResponse(
                message: $exception->getMessage(),
                statusCode: Response::HTTP_CONFLICT,
            ),

            default => null,
        };

        if ($response !== null) {
            $event->setResponse($response);
        }
    }

    private function validationErrorResponse(
        ValidationException $exception,
    ): JsonResponse {
        $errors = [];

        /** @var ConstraintViolationInterface $violation */
        foreach ($exception->violations() as $violation) {
            $errors[] = [
                'field' => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
            ];
        }

        return new JsonResponse(
            [
                'errors' => $errors,
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY,
        );
    }

    private function errorResponse(
        string $message,
        int $statusCode,
    ): JsonResponse {
        return new JsonResponse(
            [
                'error' => $message,
            ],
            $statusCode,
        );
    }
}
