<?php

declare(strict_types=1);

namespace App\Tests\Wishlist\Infrastructure\Http\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class AddWishlistItemControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $this->entityManager = self::getContainer()
            ->get(EntityManagerInterface::class);
    }

    public function testAddItem(): void
    {
        $wishlistId = $this->createWishlist();

        $this->client->request(
            method: 'POST',
            uri: sprintf('/wishlists/%d/items', $wishlistId),
            server: [
                'CONTENT_TYPE' => 'application/json',
            ],
            content: json_encode([
                'productId' => 10,
            ], JSON_THROW_ON_ERROR),
        );

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $response = json_decode(
            $this->client->getResponse()->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR,
        );

        self::assertSame($wishlistId, $response['wishlistId']);
        self::assertSame(10, $response['productId']);
        self::assertIsInt($response['id']);
    }

    public function testAddItemToNonExistentWishlist(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/wishlists/999999/items',
            server: [
                'CONTENT_TYPE' => 'application/json',
            ],
            content: json_encode([
                'productId' => 10,
            ], JSON_THROW_ON_ERROR),
        );

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testCannotAddSameProductTwice(): void
    {
        $wishlistId = $this->createWishlist();

        $payload = json_encode([
            'productId' => 10,
        ], JSON_THROW_ON_ERROR);

        $this->client->request(
            method: 'POST',
            uri: sprintf('/wishlists/%d/items', $wishlistId),
            server: [
                'CONTENT_TYPE' => 'application/json',
            ],
            content: $payload,
        );

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $this->client->request(
            method: 'POST',
            uri: sprintf('/wishlists/%d/items', $wishlistId),
            server: [
                'CONTENT_TYPE' => 'application/json',
            ],
            content: $payload,
        );

        self::assertResponseStatusCodeSame(Response::HTTP_CONFLICT);
    }

    public function testProductIdMustBePositive(): void
    {
        $wishlistId = $this->createWishlist();

        $this->client->request(
            method: 'POST',
            uri: sprintf('/wishlists/%d/items', $wishlistId),
            server: [
                'CONTENT_TYPE' => 'application/json',
            ],
            content: json_encode([
                'productId' => 0,
            ], JSON_THROW_ON_ERROR),
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = json_decode(
            $this->client->getResponse()->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR,
        );

        self::assertSame(
            [
                [
                    'field' => 'productId',
                    'message' => 'This value should be positive.',
                ],
            ],
            $response['errors'],
        );
    }

    private function createWishlist(): int
    {
        return (int) $this->entityManager
            ->getConnection()
            ->fetchOne(
                'INSERT INTO wishlists DEFAULT VALUES RETURNING id',
            );
    }
}
