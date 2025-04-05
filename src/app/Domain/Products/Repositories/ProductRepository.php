<?php

namespace App\Domain\Products\Repositories;

use App\Domain\Products\Entities\Product;
use App\Domain\Products\ValueObjects\ProductData;

interface ProductRepository
{
    public function save(Product $product): void;

    public function findByTitle(string $title): ?Product;

    public function getAllSortedByNewest(): array;
}