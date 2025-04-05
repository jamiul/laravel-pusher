<?php

namespace App\Infrastructure\Products\Repositories;

use App\Domain\Products\Entities\Product;
use App\Domain\Products\Repositories\ProductRepository;
use App\Models\Product as EloquentProduct;
use DateTimeImmutable;

class EloquentProductRepository implements ProductRepository
{
    public function save(Product $product): void
    {
        EloquentProduct::updateOrCreate(
            ['title' => $product->title],
            [
                'description' => $product->description,
                'price' => $product->price,
                'image' => $product->image,
                'created_at' => $product->createdAt,
            ]
        );
    }

    public function findByTitle(string $title): ?Product
    {
        $product = EloquentProduct::where('title', $title)->first();
        return $product ? $this->toDomainEntity($product) : null;
    }

    public function getAllSortedByNewest(): array
    {
        return EloquentProduct::orderBy('created_at', 'desc')
            ->get()
            ->map(fn($p) => $this->toDomainEntity($p))
            ->toArray();
    }

    private function toDomainEntity(EloquentProduct $product): Product
    {
        return new Product(
            title: $product->title,
            description: $product->description,
            price: $product->price,
            image: $product->image,
            createdAt: new DateTimeImmutable($product->created_at)
        );
    }
}