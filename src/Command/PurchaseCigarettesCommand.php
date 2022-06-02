<?php

namespace App\Command;

use App\Exceptions\NotEnoughMoneyException;
use App\Service\PurchaseService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:purchase-cigarettes',
    description: 'Add a short description for your command',
)]
class PurchaseCigarettesCommand extends Command
{
    private PurchaseService $purchaseService;

    public function __construct(string $name = null, PurchaseService $purchaseService)
    {
        parent::__construct($name);
        $this->purchaseService = $purchaseService;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('amount', InputArgument::REQUIRED, 'Amount packs of cigarettes')
            ->addArgument('credit', InputArgument::REQUIRED, 'Amount of money input');
    }

    /**
     * @throws NotEnoughMoneyException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $amount = (int)$input->getArgument('amount');
        $credit = $input->getArgument('credit') * 100;

        $result = $this->purchaseService->checkout($amount, $credit);
        $table = new Table($output);
        $table->setHeaders(['Coins', 'Count'])->setRows($result['coins']);

        $output->writeln("You bought {$amount} packs of cigarettes for -{$result['totalPrice']}€, each for -4,99€.");
        $output->writeln("Your change is: {$result['change']}€");
        $output->writeln("Your change in coins");

        $table->render();
        return Command::SUCCESS;
    }
}
