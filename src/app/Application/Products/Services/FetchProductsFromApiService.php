<?php

namespace App\Application\Products\Services;

use App\Domain\Products\Entities\Product;
use App\Domain\Products\ValueObjects\ProductData;
use App\Domain\Products\Repositories\ProductRepository;
use App\Domain\Products\Services\ExternalProductService;
use App\Domain\Products\Events\DomainEventDispatcher;

class FetchProductsFromApiService
{
    public function __construct(
        private ExternalProductService $externalService,
        private ProductRepository $repository,
        private DomainEventDispatcher $dispatcher
    ) {}

    public function execute(): int
    {
        $newCount = 0;
        foreach ($this->externalService->fetchProducts() as $productData) {
            if ($this->isNewProduct($productData)) {
                $product = $this->createProductEntity($productData);
                $this->repository->save($product);
                $this->dispatcher->dispatch(new NewProductAdded($product));
                $newCount++;
            }
        }
        return $newCount;
    }

    private function isNewProduct(ProductData $data): bool
    {
        return null === $this->repository->findByTitle($data->title);
    }

    private function createProductEntity(ProductData $data): Product
    {
        return new Product(
            title: $data->title,
            description: $data->description,
            price: $data->price,
            image: $data->image,
            createdAt: new \DateTimeImmutable()
        );
    }
}