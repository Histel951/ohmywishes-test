<?php

declare(strict_types=1);

namespace App\Wishlist\Infrastructure\Http\Request;

use App\Shared\Application\Exception\InvalidJsonException;
use App\Wishlist\Application\DTO\AddWishlistItemRequest;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpFoundation\Request;

final readonly class AddWishlistItemRequestFactory
{
    public function fromRequest(Request $request): AddWishlistItemRequest
    {
        try {
            $payload = $request->toArray();
        } catch (JsonException $exception) {
            throw new InvalidJsonException(
                previous: $exception,
            );
        }

        return new AddWishlistItemRequest(
            productId: $payload['productId'] ?? null,
        );
    }
}
