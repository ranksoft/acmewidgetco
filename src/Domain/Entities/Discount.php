<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Entities;

use AcmeWidgetCo\Domain\Interfaces\DiscountInterface;
use AcmeWidgetCo\Domain\Interfaces\ProductInterface;
use AcmeWidgetCo\Infrastructure\Config\Config;
use Brick\Math\Exception\MathException;
use Brick\Money\Context\CustomContext;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Money;

class Discount implements DiscountInterface
{
    /**
     * @var Money[]
     */
    private array $discounts = [];

    /**
     * @param string $currency
     */
    public function __construct(
        private readonly string $currency = Config::DEFAULT_CURRENCY
    )
    {
    }

    /**
     * @inheritdoc
     */
    public function setDiscount(string $type, Money $discount): void
    {
        $this->discounts[$type] = $discount;
    }

    /**
     * @inheritdoc
     */
    public function getDiscount(string $type): Money
    {
        return $this->discounts[$type];
    }

    /**
     * @inheritdoc
     * @throws MathException|MoneyMismatchException
     */
    public function getTotalDiscount(): Money
    {
        $totalDiscount = Money::zero($this->currency, new CustomContext(ProductInterface::PRICE_SCALE));

        foreach ($this->discounts as $discount) {
            $totalDiscount = $totalDiscount->plus($discount);
        }

        return $totalDiscount;
    }
}
