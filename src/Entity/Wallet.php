<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WalletRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert as Assertion;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ApiResource()
 * @ORM\Entity(repositoryClass=WalletRepository::class)
 */
class Wallet
{
    public const USD = 'USD';
    public const UAH = 'UAH';
    public const EUR = 'EUR';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private $walletId;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=3)
     * @Assert\Currency()
     */
    private $currency;

    /**
     * @var Collection|Category[]
     * @ORM\OneToMany(
     *     targetEntity=Category::class,
     *     mappedBy="wallet",
     *     orphanRemoval=true,
     *     cascade={"persist", "remove"}
     * )
     */
    private $categories;

    public function __construct(string $name, string $currency)
    {
        Assertion::inArray(
            $currency,
            [self::USD, self::UAH, self::EUR],
            'Wallet\'s currency must be one of: %2$s. Got: %s'
        );

        $this->changeName($name);
        $this->currency = $currency;
        $this->categories = new ArrayCollection();
    }

    public function getWalletId(): int
    {
        return $this->walletId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function changeName(string $newWalletName): void
    {
        Assertion::maxLength($newWalletName, 50, 'Wallet\'s name must be up to 50 characters');

        $this->name = $newWalletName;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function addIncomeCategory(string $name): void
    {
        $this->categories->add(Category::createIncome($this, $name));
    }

    public function addExpenseCategory(string $name): void
    {
        $this->categories->add(Category::createExpense($this, $name));
    }

    public function removeCategory(int $categoryId): void
    {
        foreach ($this->categories as $category) {
            if ($category->getCategoryId() === $categoryId) {
                $this->categories->removeElement($category);

                return;
            }
        }
    }

    public function changeCategoryName(int $categoryId, string $newCategoryName): void
    {
        $category = $this->findCategoryById($categoryId);
        Assertion::notNull($category, sprintf('Category #%d not found', $categoryId));

        if ($category->getName() === $newCategoryName) {
            return;
        }

        Assertion::null(
            $this->findCategoryByName($newCategoryName),
            sprintf('Category with name "%s" exists', $newCategoryName)
        );

        $category->changeName($newCategoryName);
    }

    /**
     * @return list<int, array>
     */
    public function getIncomeCategories(): array
    {
        return $this->categories
            ->filter(
                function (Category $category) {
                    return $category->isIncome();
                }
            )
            ->map(
                function (Category $category) {
                    return [
                        'categoryId' => $category->getCategoryId(),
                        'name'        => $category->getName(),
                    ];
                }
            )
            ->toArray();
    }

//    /**
//     * @return Category[]|Collection
//     */
//    public function getCategories(): Collection
//    {
//        return $this->categories;
//    }

    /**
     * @return list<int, array<string, string>>
     */
    public function getExpenseCategories(): array
    {
        return $this->categories
            ->filter(
                function (Category $category) {
                    return $category->isExpense();
                }
            )
            ->map(
                function (Category $category) {
                    return [
                        'categoryId' => $category->getCategoryId(),
                        'name'        => $category->getName(),
                    ];
                }
            )
            ->toArray()
        ;
    }

    private function findCategoryByName(string $categoryName): ?Category
    {
        foreach ($this->categories as $category) {
            if ($category->getName() === $categoryName) {
                return $category;
            }
        }

        return null;
    }

    private function findCategoryById(int $categoryId): ?Category
    {
        foreach ($this->categories as $category) {
            if ($category->getCategoryId() === $categoryId) {
                return $category;
            }
        }

        return null;
    }
}
