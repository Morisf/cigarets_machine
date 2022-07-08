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
        1,
    ];

    private array $change = [];

    public function getCoins(int $debit): array
    {
        $this->calculateChange($debit);

        return $this->change;
    }

    public function calculateChange(int $debit): int
    {
        if ($debit > self::AVAILABLE_COINS[0]) {
            $lefts = $debit % self::AVAILABLE_COINS[0];
            $topCoins = floor($debit / self::AVAILABLE_COINS[0]);

            $this->change[(string)self::AVAILABLE_COINS[0]] = [
                'coinVal' => self::AVAILABLE_COINS[0],
                'coinCount' => $topCoins,
            ];

            if (!($lefts)) {
                return 0;
            } else {
                $debit = $debit - ($topCoins * self::AVAILABLE_COINS[0]);
            }
        }

        foreach (self::AVAILABLE_COINS as $coin) {
            if ($coin > $debit) {
                continue;
            }
            $this->change[(string)$coin] = [
                'coinVal' => $coin,
                'coinCount' => isset($this->change[(string)$coin]) ? $this->change[(string)$coin]['coinCount'] + 1 : 1,
            ];

            $debit -= $coin;
            if ($debit >= $coin) {
                $debit = $this->calculateChange($debit);
            }
        }

        return $debit;
    }
}
