<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * ApiResource()
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    private const TYPE__INCOME = 'income';

    private const TYPE__EXPENSE = 'expense';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private $categoryId;

    /**
     * @var Wallet
     *
     * @ORM\ManyToOne(targetEntity=Wallet::class, inversedBy="categories")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="wallet_id", name="wallet_id")
     */
    private $wallet;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=10)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    private function __construct(Wallet $wallet, string $type, string $name)
    {
        Assert::inArray($type, [self::TYPE__INCOME, self::TYPE__EXPENSE]);

        $this->wallet = $wallet;
        $this->type = $type;
        $this->changeName($name);
    }

    public static function createIncome(Wallet $wallet, string $name): Category
    {
        return new self($wallet, self::TYPE__INCOME, $name);
    }

    public static function createExpense(Wallet $wallet, string $name): Category
    {
        return new self($wallet, self::TYPE__EXPENSE, $name);
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isIncome(): bool
    {
        return self::TYPE__INCOME === $this->type;
    }

    public function isExpense(): bool
    {
        return self::TYPE__EXPENSE === $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function changeName(string $newName): void
    {
        Assert::maxLength($newName, 50);

        $this->name = $newName;
    }
}
