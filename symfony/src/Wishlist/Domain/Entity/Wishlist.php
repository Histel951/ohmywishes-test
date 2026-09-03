<?php

declare(strict_types=1);

namespace App\Wishlist\Domain\Entity;

use App\Wishlist\Domain\Exception\WishlistItemAlreadyExistsException;
use App\Wishlist\Domain\Exception\WishlistItemDoesNotBelongToWishlistException;

final class Wishlist
{
    /**
     * @var list<WishlistItem>
     */
    private array $items = [];

    public function __construct(
        private readonly int $id,
    ) {
    }

    public function id(): int
    {
        return $this->id;
    }

    public function addItem(int $productId): WishlistItem
    {
        if ($this->hasProduct($productId)) {
            throw new WishlistItemAlreadyExistsException(
                wishlistId: $this->id,
                productId: $productId,
            );
        }

        $item = WishlistItem::create(
            wishlist: $this,
            productId: $productId,
        );

        $this->items[] = $item;

        return $item;
    }

    public function attachItem(WishlistItem $item): void
    {
        if ($item->wishlist()->id() !== $this->id()) {
            throw new WishlistItemDoesNotBelongToWishlistException(
                wishlistId: $this->id(),
                productId: $item->productId(),
            );
        }

        if ($this->hasProduct($item->productId())) {
            return;
        }

        $this->items[] = $item;
    }

    public function hasProduct(int $productId): bool
    {
        return array_any(
            $this->items,
            fn (WishlistItem $item): bool =>
                $item->productId() === $productId,
        );
    }

    /**
     * @return list<WishlistItem>
     */
    public function items(): array
    {
        return $this->items;
    }
}
