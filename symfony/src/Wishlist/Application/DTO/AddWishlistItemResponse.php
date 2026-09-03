<?php

declare(strict_types=1);

namespace App\Wishlist\Application\DTO;

use App\Wishlist\Domain\Entity\WishlistItem;

final readonly class AddWishlistItemResponse
{
    public function __construct(
        public int $id,
        public int $wishlistId,
        public int $productId,
    ) {
    }

    public static function fromEntity(WishlistItem $item): self
    {
        $id = $item->id();

        if ($id === null) {
            throw new \LogicException(
                'Wishlist item ID is not initialized.',
            );
        }

        return new self(
            id: $id,
            wishlistId: $item->wishlist()->id(),
            productId: $item->productId(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'wishlistId' => $this->wishlistId,
            'productId' => $this->productId,
        ];
    }
}
