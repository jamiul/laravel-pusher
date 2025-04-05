<?php

namespace App\Infrastructure\Services\DTO;

use App\Domain\Products\ValueObjects\ProductData;

class FakeStoreProductMapper
{
    public static function map(array $apiResponse): ProductData
    {
        return new ProductData(
            title: $apiResponse['title'],
            description: $apiResponse['description'],
            price: (float) $apiResponse['price'],
            image: $apiResponse['image'] ?? null
        );
    }
}