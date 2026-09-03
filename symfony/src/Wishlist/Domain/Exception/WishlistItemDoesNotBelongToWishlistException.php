<?php

declare(strict_types=1);

namespace App\Wishlist\Domain\Exception;

use LogicException;

final class WishlistItemDoesNotBelongToWishlistException extends LogicException
{
    public function __construct(
        int $wishlistId,
        int $productId,
    ) {
        parent::__construct(
            sprintf(
                'Wishlist item with product "%d" does not belong to wishlist "%d".',
                $productId,
                $wishlistId,
            ),
        );
    }
}
