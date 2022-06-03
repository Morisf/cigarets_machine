<?php

namespace App\Tests\Unit\Command;

use App\Exceptions\NotEnoughMoneyException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class PurchaseCigarettesCommandTest extends KernelTestCase
{
    public function testSuccess(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:purchase-cigarettes');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'amount' => 2,
            'credit' => 10.00
        ]);

        $commandTester->assertCommandIsSuccessful();
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Your change is: 0.02€', $output);
        $this->assertStringContainsString('| 2     | 1     |', $output);
    }

    public function testFailed(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $this->expectException(NotEnoughMoneyException::class);
        $command = $application->find('app:purchase-cigarettes');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'amount' => 2,
            'credit' => 0
        ]);
    }

    public function testZeroItemsJustReturnMoney(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:purchase-cigarettes');

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'amount' => 0,
            'credit' => 10
        ]);

        $commandTester->assertCommandIsSuccessful();
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('You bought 0 packs of cigarettes for -0€, each for 0.', $output);
        $this->assertStringContainsString('| 25    | 40    |', $output);
    }
}
