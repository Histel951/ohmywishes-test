<?php

declare(strict_types=1);

namespace App\Wishlist\Application\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class AddWishlistItemRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\NotNull]
        #[Assert\Type('integer')]
        #[Assert\Positive]
        public mixed $productId,
    ) {
    }
}
