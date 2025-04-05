<?php

namespace App\Domain\Products\Services;

use App\Domain\Products\ValueObjects\ProductData;

interface ExternalProductService
{
    /** @return ProductData[] */
    public function fetchProducts(): array;

    public function fetchRandomProduct(): ProductData;
}