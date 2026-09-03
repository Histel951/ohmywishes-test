<?php

declare(strict_types=1);

namespace App\Wishlist\Domain\Exception;

final class WishlistItemAlreadyExistsException extends \DomainException
{
    public function __construct(
        int $wishlistId,
        int $productId,
    ) {
        parent::__construct(
            sprintf(
                'Product %d already exists in wishlist %d.',
                $productId,
                $wishlistId,
            ),
        );
    }
}
