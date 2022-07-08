<?php

namespace App\Tests\Unit\Service;

use App\Service\MoneyService;
use PHPUnit\Framework\TestCase;

class MoneyServiceTest extends TestCase
{
    public function testCoinsMillion(): void
    {
        $service = new MoneyService();
        $result = $service->getCoins(1000000);
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals(25, $result['25']['coinVal']);
        $this->assertEquals(40000, $result['25']['coinCount']);
    }

    public function testCoinsMillionAndOneCent(): void
    {
        $service = new MoneyService();
        $result = $service->getCoins(1000001);
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertEquals(25, $result['25']['coinVal']);
        $this->assertEquals(40000, $result['25']['coinCount']);
    }

    public function testCoinsReturnOneCents(): void
    {
        $service = new MoneyService();
        $result = $service->getCoins(1);
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals(1, $result['1']['coinVal']);
    }

    public function testCoinsReturnSeventyOneCents(): void
    {
        $service = new MoneyService();
        $result = $service->getCoins(71);
        $this->assertIsArray($result);
        $this->assertCount(3, $result);
        $this->assertEquals(2, $result['25']['coinCount']);
        $this->assertEquals(1, $result['20']['coinCount']);
        $this->assertEquals(1, $result['1']['coinCount']);
    }

    public function testCoinsReturnEightCents(): void
    {
        $service = new MoneyService();
        $result = $service->getCoins(8);
        $this->assertCount(3, $result);
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['5']['coinCount']);
        $this->assertEquals(1, $result['2']['coinCount']);
        $this->assertEquals(1, $result['1']['coinCount']);
    }

    public function testCoinsReturnZero(): void
    {
        $service = new MoneyService();
        $result = $service->getCoins(0);
        $this->assertIsArray($result);
        $this->assertCount(0, $result);
    }
}
