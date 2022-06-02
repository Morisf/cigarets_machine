<?php

namespace App\Service;

class MoneyService
{
    public const AVAILABLE_COINS = [
        25,
        20,
        10,
        5,
        2,
        1
    ];

    private array $change = [];

    public function getCoins(int $debit): array
    {
        $this->calculateChange($debit);
        return $this->change;
    }

    public function calculateChange(int $debit): int
    {
        foreach (self::AVAILABLE_COINS as $coin) {
            if ($coin > $debit) {
                continue;
            }
            $this->change[(string)$coin] = [
                'coinVal' => $coin,
                'coinCount' => isset($this->change[(string)$coin]) ? $this->change[(string)$coin]['coinCount'] + 1: 1
            ];

            $debit -= $coin;
            if ($debit >= $coin) {
                $debit = $this->calculateChange($debit);
            }
        }

        return $debit;
    }
}
