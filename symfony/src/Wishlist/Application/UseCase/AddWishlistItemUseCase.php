<?php

declare(strict_types=1);

namespace App\Wishlist\Application\UseCase;

use App\Wishlist\Application\DTO\AddWishlistItemRequest;
use App\Wishlist\Application\DTO\AddWishlistItemResponse;
use App\Wishlist\Domain\Exception\WishlistNotFoundException;
use App\Wishlist\Domain\Repository\WishlistRepositoryInterface;

final readonly class AddWishlistItemUseCase
{
    public function __construct(
        private WishlistRepositoryInterface $wishlistRepository,
    ) {
    }

    public function execute(
        int $wishlistId,
        AddWishlistItemRequest $request,
    ): AddWishlistItemResponse {
        $wishlist = $this->wishlistRepository->findById($wishlistId);

        if ($wishlist === null) {
            throw new WishlistNotFoundException($wishlistId);
        }

        $item = $wishlist->addItem($request->productId);

        $this->wishlistRepository->save($wishlist);

        return AddWishlistItemResponse::fromEntity($item);
    }
}
