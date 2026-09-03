<?php

declare(strict_types=1);

namespace App\Wishlist\Domain\Repository;

use App\Wishlist\Domain\Entity\Wishlist;

interface WishlistRepositoryInterface
{
    public function findById(int $id): ?Wishlist;

    public function save(Wishlist $wishlist): void;
}
