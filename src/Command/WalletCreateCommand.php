<?php

namespace App\Command;

use App\Entity\Wallet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class WalletCreateCommand extends Command
{
    protected static $defaultName = 'family-budget:wallet:create';
    protected static $defaultDescription = 'Create new wallet';

    /** @var EntityManagerInterface */
    private $objectManager;

    public function __construct(EntityManagerInterface $objectManager)
    {
        parent::__construct();
        $this->objectManager = $objectManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Wallet name')
            ->addArgument('currency', InputArgument::REQUIRED, 'Wallet currency')
            ->addOption('add-default-categories', 'c', InputOption::VALUE_NONE, 'Add default categories to wallet')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $walletName = $input->getArgument('name');
        $currency = $input->getArgument('currency');

        $wallet = new Wallet($walletName, $currency);
        if ($input->getOption('add-default-categories')) {
            // Income
            $wallet->addIncomeCategory('Award');
            $wallet->addIncomeCategory('Gift');
            $wallet->addIncomeCategory('Salary');
            $wallet->addIncomeCategory('Selling');
            $wallet->addIncomeCategory('Other Income');
            // Expense
            $wallet->addExpenseCategory('Food & Beverage');
            $wallet->addExpenseCategory('Education');
            $wallet->addExpenseCategory('Entertainment');
            $wallet->addExpenseCategory('Gifts & Donations');
            $wallet->addExpenseCategory('Health & Fitness');
            $wallet->addExpenseCategory('Shopping');
            $wallet->addExpenseCategory('Fees & Charges');
            $wallet->addExpenseCategory('Transportation');
            $wallet->addExpenseCategory('Other Expense');
        }

        $this->objectManager->persist($wallet);
        $this->objectManager->flush();

        $io->success(sprintf('New Wallet #%d "%s"', $wallet->getWalletId(), $wallet->getName()));
        if ($input->getOption('add-default-categories')) {
            $io->success(
                'With income categories:' . PHP_EOL
                . implode(PHP_EOL, array_map(function ($item) { return $item['name']; }, $wallet->getIncomeCategories()))
            );
            $io->success(
                'With expense categories:' . PHP_EOL
                . implode(PHP_EOL, array_map(function ($item) { return $item['name']; }, $wallet->getExpenseCategories()))
            );
        }

        return Command::SUCCESS;
    }
}
