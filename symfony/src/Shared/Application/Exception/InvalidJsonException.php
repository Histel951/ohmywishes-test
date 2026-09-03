<?php

declare(strict_types=1);

namespace App\Shared\Application\Exception;

use Symfony\Component\HttpFoundation\Exception\JsonException;

final class InvalidJsonException extends \RuntimeException
{
    public function __construct(JsonException $previous)
    {
        parent::__construct('Invalid JSON request body.', 0, $previous);
    }
}
