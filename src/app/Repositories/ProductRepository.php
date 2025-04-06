<?php

namespace App\Repositories;

use App\Events\NewProductAdded;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(protected Product $model)
    {
    }

    public function getAllPaginated(int $perPage = 9): LengthAwarePaginator
    {
        return $this->model->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function find(int $id): ?Product
    {
        return $this->model->find($id);
    }

    public function create(array $data): Product
    {
        $product = $this->model->create($data);
        event(new NewProductAdded($product));
        return $product;
    }

    public function update(Product $product, array $data): bool
    {
        return $product->update($data);
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }

    public function firstOrCreate(array $search, array $data): Product
    {
        $product = $this->model->firstOrCreate($search, $data);
        
        if ($product->wasRecentlyCreated) {
            event(new NewProductAdded($product));
        }
        
        return $product;
    }

    public function fetchFromApi(): array
    {
        $response = Http::get('https://fakestoreapi.com/products');
        $apiProducts = $response->json();
        $newProducts = [];

        foreach ($apiProducts as $apiProduct) {
            $product = $this->firstOrCreate(
                ['title' => $apiProduct['title']],
                [
                    'description' => $apiProduct['description'],
                    'price' => $apiProduct['price'],
                    'image' => $apiProduct['image'] ?? null
                ]
            );

            if ($product->wasRecentlyCreated) {
                $newProducts[] = $product;
            }
        }

        return $newProducts;
    }

    public function addRandomProduct(): Product
    {
        $response = Http::get('https://fakestoreapi.com/products/' . rand(1, 20));
        $apiProduct = $response->json();

        return $this->create([
            'title' => $apiProduct['title'] . ' (Copy ' . rand(100, 999) . ')',
            'description' => $apiProduct['description'],
            'price' => $apiProduct['price'],
            'image' => $apiProduct['image'] ?? null
        ]);
    }
} 