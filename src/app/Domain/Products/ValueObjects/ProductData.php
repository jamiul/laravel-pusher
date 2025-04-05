<?php

namespace App\Domain\Products\ValueObjects;

class ProductData
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly float $price,
        public readonly ?string $image
    ) {}
}