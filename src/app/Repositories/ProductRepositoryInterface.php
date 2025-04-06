<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    public function getAllPaginated(int $perPage = 9): LengthAwarePaginator;
    public function find(int $id): ?Product;
    public function create(array $data): Product;
    public function update(Product $product, array $data): bool;
    public function delete(Product $product): bool;
    public function firstOrCreate(array $search, array $data): Product;
    public function fetchFromApi(): array;
    public function addRandomProduct(): Product;
} 