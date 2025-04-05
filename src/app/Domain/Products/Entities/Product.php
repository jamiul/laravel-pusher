<?php

namespace App\Domain\Products\Entities;

use DateTimeImmutable;

class Product
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly float $price,
        public readonly ?string $image,
        public readonly ?DateTimeImmutable $createdAt
    ) {}

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'image' => $this->image,
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s'),
        ];
    }
}