<?php

declare(strict_types=1);

namespace App\Wishlist\Domain\Exception;

final class WishlistNotFoundException extends \DomainException
{
    public function __construct(
        public readonly int $wishlistId,
    ) {
        parent::__construct(
            sprintf(
                'Wishlist with id: %d not found.',
                $wishlistId,
            ),
        );
    }
}
