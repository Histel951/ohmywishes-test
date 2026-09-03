<?php

declare(strict_types=1);

namespace App\Wishlist\Infrastructure\Persistence\Doctrine;

use App\Wishlist\Domain\Entity\Wishlist;
use App\Wishlist\Domain\Entity\WishlistItem;
use App\Wishlist\Domain\Repository\WishlistRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrineWishlistRepository implements WishlistRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function findById(int $id): ?Wishlist
    {
        $wishlist = $this->entityManager
            ->getRepository(Wishlist::class)
            ->find($id);

        if ($wishlist === null) {
            return null;
        }

        $items = $this->entityManager
            ->getRepository(WishlistItem::class)
            ->findBy([
                'wishlist' => $wishlist,
            ]);

        foreach ($items as $item) {
            $wishlist->attachItem($item);
        }

        return $wishlist;
    }

    public function save(Wishlist $wishlist): void
    {
        foreach ($wishlist->items() as $item) {
            if ($item->id() === null) {
                $this->entityManager->persist($item);
            }
        }

        $this->entityManager->flush();
    }
}
