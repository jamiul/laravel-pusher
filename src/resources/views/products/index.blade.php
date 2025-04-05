@extends('products.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2>Products</h2>
                        <div>
                            <a href="{{ route('products.fetch') }}" class="btn btn-primary">Fetch All Products</a>
                            <a href="{{ route('products.add-random') }}" class="btn btn-success ml-2">Add Random Product</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row" id="products-container">
                        @foreach ($products as $product)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    @if ($product->image)
                                        <img src="{{ $product->image }}" class="card-img-top" alt="{{ $product->title }}" style="height: 200px; object-fit: contain; padding: 10px;">
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $product->title }}</h5>
                                        <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                                        <p class="card-text"><strong>Price: ${{ number_format($product->price, 2) }}</strong></p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endsection
