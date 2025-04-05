<?php

namespace App\Http\Controllers;

use App\Application\Products\Services\FetchProductsFromApiService;
use App\Application\Products\Services\AddRandomProductService;
use App\Domain\Products\Repositories\ProductRepository;

class ProductController extends Controller
{
    public function index(ProductRepository $repository)
    {
        return view('products.index', [
            'products' => $repository->getAllSortedByNewest(),
            'pusherKey' => env('PUSHER_APP_KEY'),
            'pusherCluster' => env('PUSHER_APP_CLUSTER')
        ]);
    }

    public function fetchFromApi(FetchProductsFromApiService $service)
    {
        $count = $service->execute();
        return redirect()->route('products.index')
            ->with('success', "Added {$count} new products");
    }

    public function addRandomProduct(AddRandomProductService $service)
    {
        $service->execute();
        return redirect()->route('products.index')
            ->with('success', 'Random product added successfully');
    }
}