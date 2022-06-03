<?php

namespace App\Service;

use App\Exceptions\NotEnoughMoneyException;
use JetBrains\PhpStorm\ArrayShape;

class PurchaseService
{
    private MoneyService $moneyService;
    private ProductService $productService;

    public function __construct(MoneyService $moneyService, ProductService $productService)
    {
        $this->moneyService = $moneyService;
        $this->productService = $productService;
    }

    /**
     * @param int $items
     * @param int $credit
     * @return array
     * @throws NotEnoughMoneyException
     */
    #[ArrayShape(['coins' => "array", 'totalPrice' => "float", 'change' => "float", 'itemPrice' => "float"])] public
    function checkout(
        int $items,
        int $credit
    ): array {
        if (!$items) {
            return [
                'coins' => $this->moneyService->getCoins($credit),
                'totalPrice' => 0,
                'change' => $credit / 100,
                'itemPrice' => 0,
            ];
        }

        $product = $this->productService->getProduct(0);
        $totalPrice = $product['price'] * $items;

        if ($totalPrice > $credit) {
            $totalInMoney = $totalPrice / 100;
            $shortage = ($totalPrice - $credit) / 100;
            throw new NotEnoughMoneyException(
                "Not enough money, requested {$items} of product {$product['title']} in sum is: {$totalInMoney}, please add: {$shortage} more"
            );
        }

        return [
            'coins' => $this->moneyService->getCoins($credit - $totalPrice),
            'totalPrice' => $totalPrice / 100,
            'change' => ($credit - $totalPrice) / 100,
            'itemPrice' => $product['price'] / 100,
        ];
    }
}
