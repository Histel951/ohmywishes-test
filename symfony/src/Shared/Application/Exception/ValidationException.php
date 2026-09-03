<?php

declare(strict_types=1);

namespace App\Shared\Application\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ValidationException extends \RuntimeException
{
    public function __construct(
        private readonly ConstraintViolationListInterface $violations,
    ) {
        parent::__construct('Validation failed.');
    }

    public function violations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }
}
