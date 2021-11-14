<?php

namespace App\Command;

use App\Repository\WalletRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class WalletAddCategoryCommand extends Command
{
    protected static $defaultName = 'family-budget:wallet:add-category';
    protected static $defaultDescription = 'Add new category to wallet';

    /** @var EntityManagerInterface */
    private $objectManager;

    /** @var WalletRepository */
    private $walletRepository;

    public function __construct(
        EntityManagerInterface $objectManager,
        WalletRepository $walletRepository
    ) {
        parent::__construct();
        $this->objectManager = $objectManager;
        $this->walletRepository = $walletRepository;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('category-name', InputArgument::REQUIRED, 'Category name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        while (empty($walletName)) {
            $wallets = $this->walletRepository->findAll();
            $walletArray = [];
            foreach ($wallets as $wallet) {
                $walletArray[$wallet->getWalletId()] = $wallet->getName();
            }
            $question = new ChoiceQuestion('Choice wallet', $walletArray);

            $walletName = $io->askQuestion($question);
        }

        $wallet = $this->walletRepository->findOneByName($walletName);
        if (null === $wallet) {
            $io->error(sprintf('Wallet "%s" not found', $walletName));

            return Command::FAILURE;
        }

        $categoryName = $input->getArgument('category-name');
        while (empty($categoryType)) {
            $categoryType = $io->askQuestion(new ChoiceQuestion('Choice category type', ['income', 'expense']));
        }

        if ('income' === $categoryType) {
            $wallet->addIncomeCategory($categoryName);
        } elseif ('expense' === $categoryType) {
            $wallet->addExpenseCategory($categoryName);
        } else {
            $io->error(sprintf('Unexpected category type: "%s"', $categoryType));

            return Command::FAILURE;
        }

        $this->objectManager->flush();

        $io->success('Done');

        return Command::SUCCESS;
    }
}
