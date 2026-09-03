<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Validation;

use App\Shared\Application\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class RequestValidator
{
    public function __construct(
        private ValidatorInterface $validator,
    ) {
    }

    public function validate(object $request): void
    {
        $violations = $this->validator->validate($request);

        if ($violations->count() > 0) {
            throw new ValidationException($violations);
        }
    }
}
