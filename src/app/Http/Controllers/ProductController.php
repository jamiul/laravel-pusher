<?php

namespace App\Http\Controllers;

use App\Events\NewProductAdded;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        
        return view('products.index', [
            'products' => $products,
            'pusherKey' => env('PUSHER_APP_KEY'),
            'pusherCluster' => env('PUSHER_APP_CLUSTER')
        ]);
    }

    public function fetchFromApi()
    {
        $response = Http::get('https://fakestoreapi.com/products');
        $apiProducts = $response->json();

        $newProducts = [];

        foreach ($apiProducts as $apiProduct) {
            $product = Product::firstOrCreate(
                ['title' => $apiProduct['title']],
                [
                    'description' => $apiProduct['description'],
                    'price' => $apiProduct['price'],
                    'image' => $apiProduct['image'] ?? null
                ]
            );

            if ($product->wasRecentlyCreated) {
                $newProducts[] = $product;
                event(new NewProductAdded($product));
            }
        }

        return redirect()->route('products.index')
            ->with('success', count($newProducts) . ' new products added.');
    }

    public function addRandomProduct()
    {
        $response = Http::get('https://fakestoreapi.com/products/' . rand(1, 20));
        $apiProduct = $response->json();

        $product = new Product();
        $product->title = $apiProduct['title'] . ' (Copy ' . rand(100, 999) . ')';
        $product->description = $apiProduct['description'];
        $product->price = $apiProduct['price'];
        $product->image = $apiProduct['image'] ?? null;
        $product->save();

        event(new NewProductAdded($product));

        return redirect()->route('products.index')
            ->with('success', 'Random product added successfully.');
    }
}
