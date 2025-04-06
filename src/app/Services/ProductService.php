<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    public function __construct(protected ProductRepositoryInterface $productRepository)
    {
    }

    public function getAllProducts(int $perPage = 9): LengthAwarePaginator
    {
        return $this->productRepository->getAllPaginated($perPage);
    }

    public function updateProduct(Product $product, array $data): bool
    {
        return $this->productRepository->update($product, $data);
    }

    public function deleteProduct(Product $product): bool
    {
        return $this->productRepository->delete($product);
    }

    public function fetchProductsFromApi(): array
    {
        return $this->productRepository->fetchFromApi();
    }

    public function addRandomProduct(): Product
    {
        return $this->productRepository->addRandomProduct();
    }
} 