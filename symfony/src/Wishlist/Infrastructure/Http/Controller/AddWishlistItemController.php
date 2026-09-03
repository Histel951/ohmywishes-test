<?php

declare(strict_types=1);

namespace App\Wishlist\Infrastructure\Http\Controller;

use App\Shared\Infrastructure\Validation\RequestValidator;
use App\Wishlist\Application\UseCase\AddWishlistItemUseCase;
use App\Wishlist\Infrastructure\Http\Request\AddWishlistItemRequestFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final readonly class AddWishlistItemController
{
    #[Route(
        '/wishlists/{id}/items',
        name: 'wishlist_items_add',
        requirements: ['id' => '\d+'],
        methods: ['POST'],
    )]
    public function __invoke(
        int $id,
        Request $request,
        AddWishlistItemUseCase $useCase,
        RequestValidator $requestValidator,
        AddWishlistItemRequestFactory $addWishlistItemRequestFactory,
    ): JsonResponse {
        $dto = $addWishlistItemRequestFactory->fromRequest($request);

        $requestValidator->validate($dto);

        $response = $useCase->execute(
            wishlistId: $id,
            request: $dto,
        );

        return new JsonResponse(
            $response->toArray(),
            Response::HTTP_CREATED,
        );
    }
}
