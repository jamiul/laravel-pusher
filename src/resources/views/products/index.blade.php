@php
    use Illuminate\Support\Str;
@endphp

@extends('products.layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Products</h2>
                        <div>
                            <a href="{{ route('products.fetch') }}" class="btn btn-primary">Fetch All Products</a>
                            <a href="{{ route('products.add-random') }}" class="btn btn-success ms-2">Add Random Product</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row g-4" id="products-container">
                        @foreach ($products as $product)
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm hover-shadow transition">
                                    @if ($product->image)
                                        <img src="{{ $product->image }}" class="card-img-top" alt="{{ $product->title }}" style="height: 200px; object-fit: contain; padding: 10px;">
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title text-truncate">{{ $product->title }}</h5>
                                        <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                                        <p class="card-text"><strong class="text-primary">${{ number_format($product->price, 2) }}</strong></p>
                                        <div class="d-flex justify-content-end gap-2 mt-3">
                                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this product?')">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($products->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted">
                                Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products
                            </div>
                            <div>
                                {{ $products->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}
.transition {
    transition: all 0.3s ease;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if Pusher is available
        if (typeof Pusher !== 'undefined') {
            // Initialize Pusher
            const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
                cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
                forceTLS: true
            });

            console.log('Pusher: ','{{ config('broadcasting.connections.pusher.key') }}');
            console.log('Cluster: ','{{ config('broadcasting.connections.pusher.options.cluster') }}');

            // Subscribe to the products channel
            const channel = pusher.subscribe('products');
            console.log('Subscribed to channel: products');

            // Listen for new product events
            channel.bind('NewProductAdded', function(data) {
                console.log('Received event: NewProductAdded', data);
                const product = data.product;

                // Create a notification
                const notification = document.createElement('div');
                notification.className = 'alert alert-info alert-dismissible fade show';
                notification.innerHTML = `
                    New product added: <strong>${product.title}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                `;
                document.querySelector('.card-body').prepend(notification);

                // Auto dismiss notification after 5 seconds
                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => notification.remove(), 150);
                }, 5000);

                // Create new product card
                const productCard = `
                    <div class="col-md-4 mb-4 product-item-${product.id}">
                        <div class="card h-100 new-product">
                            ${product.image ? `<img src="${product.image}" class="card-img-top" alt="${product.title}" style="height: 200px; object-fit: contain; padding: 10px;">` : ''}
                            <div class="card-body">
                                <h5 class="card-title">${product.title}</h5>
                                <p class="card-text">${product.description.length > 100 ? product.description.substring(0, 100) + '...' : product.description}</p>
                                <p class="card-text"><strong>Price: $${parseFloat(product.price).toFixed(2)}</strong></p>
                            </div>
                        </div>
                    </div>
                `;

                // Prepend the new product to the container
                const container = document.getElementById('products-container');
                container.insertAdjacentHTML('afterbegin', productCard);

                // Highlight the new product
                const newProduct = container.querySelector('.new-product');
                newProduct.classList.add('border-success', 'border-3');
                newProduct.classList.remove('new-product');

                // Add animation to make it stand out
                newProduct.style.animation = 'fadeIn 1s';

                // Remove highlight after 3 seconds
                setTimeout(() => {
                    newProduct.classList.remove('border-success', 'border-3');
                    newProduct.style.transition = 'border-color 0.5s ease';
                }, 3000);
            });

            // Add event listener for connection status
            pusher.connection.bind('state_change', function(states) {
                console.log('Pusher connection state:', states.current);
            });

            // Handle connection errors
            pusher.connection.bind('error', function(err) {
                console.error('Pusher connection error:', err);
            });
        } else {
            console.error('Pusher is not loaded. Make sure to include the Pusher library.');
        }
    });
</script>
@endsection
