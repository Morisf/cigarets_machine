<?php

namespace App\Service;

class ProductService
{
    public const PRODUCTS = [
        [
            'title' => 'Pack of Cigarettes',
            'price' => 499
        ]
    ];

    public function getProduct(int $productId): array
    {
        return self::PRODUCTS[$productId];
    }
}
