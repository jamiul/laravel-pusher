<?php

namespace App\Application\Products\Services;

use App\Domain\Products\Services\ExternalProductService;
use App\Domain\Products\Repositories\ProductRepository;
use App\Domain\Products\Events\NewProductAdded;
use App\Domain\Products\Events\DomainEventDispatcher;

class AddRandomProductService
{
    public function __construct(
        private ExternalProductService $externalProductService,
        private ProductRepository $productRepository,
        private DomainEventDispatcher $dispatcher
    ) {}

    public function execute(): array
    {
        $product = $this->externalProductService->fetchRandomProduct();
        $this->productRepository->save($product);
        $this->dispatcher->dispatch(new NewProductAdded($product));
        return $product->toArray();
    }
}
