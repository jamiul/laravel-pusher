<?php

namespace App\Http\Controllers;

use App\Events\NewProductAdded;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService)
    {
    }

    public function index()
    {
        $products = $this->productService->getAllProducts();
        
        return view('products.index', [
            'products' => $products,
            'pusherKey' => env('PUSHER_APP_KEY'),
            'pusherCluster' => env('PUSHER_APP_CLUSTER')
        ]);
    }

    public function fetchFromApi()
    {
        $newProducts = $this->productService->fetchProductsFromApi();

        return redirect()->route('products.index')
            ->with('success', count($newProducts) . ' new products added.');
    }

    public function addRandomProduct()
    {
        $this->productService->addRandomProduct();

        return redirect()->route('products.index')
            ->with('success', 'Random product added successfully.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|url'
        ]);

        $this->productService->updateProduct($product, $validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->productService->deleteProduct($product);

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
