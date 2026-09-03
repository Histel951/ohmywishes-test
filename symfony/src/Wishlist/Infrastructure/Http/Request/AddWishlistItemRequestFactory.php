<?php

declare(strict_types=1);

namespace App\Wishlist\Infrastructure\Http\Request;

use App\Wishlist\Application\DTO\AddWishlistItemRequest;
use Symfony\Component\HttpFoundation\Request;

final readonly class AddWishlistItemRequestFactory
{
    public function fromRequest(Request $request): AddWishlistItemRequest
    {
        $payload = $request->toArray();

        return new AddWishlistItemRequest(
            productId: $payload['productId'] ?? null,
        );
    }
}
