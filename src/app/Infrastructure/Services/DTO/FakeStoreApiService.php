<?php

namespace App\Infrastructure\Services;

use App\Domain\Products\Services\ExternalProductService;
use App\Domain\Products\ValueObjects\ProductData;
use App\Infrastructure\Services\DTO\FakeStoreProductMapper;

class FakeStoreApiService implements ExternalProductService
{
    public function fetchProducts(): array
    {
        $response = Http::get('https://fakestoreapi.com/products');
        return array_map(
            fn($item) => FakeStoreProductMapper::map($item),
            $response->json()
        );
    }

    public function fetchRandomProduct(): ProductData
    {
        $response = Http::get('https://fakestoreapi.com/products/'.rand(1, 20));
        $data = $response->json();

        return new ProductData(
            title: $data['title'] . ' (Copy ' . rand(100, 999) . ')',
            description: $data['description'],
            price: (float) $data['price'],
            image: $data['image'] ?? null
        );
    }
}