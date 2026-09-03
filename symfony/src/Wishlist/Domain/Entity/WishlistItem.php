<?php

declare(strict_types=1);

namespace App\Wishlist\Domain\Entity;

final class WishlistItem
{
    public function __construct(
        private ?int $id,
        private Wishlist $wishlist,
        private int $productId,
    ) {
    }

    public static function create(
        Wishlist $wishlist,
        int $productId,
    ): self {
        return new self(
            id: null,
            wishlist: $wishlist,
            productId: $productId,
        );
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function wishlist(): Wishlist
    {
        return $this->wishlist;
    }

    public function productId(): int
    {
        return $this->productId;
    }
}
