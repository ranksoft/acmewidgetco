<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Entities;

use AcmeWidgetCo\Domain\Interfaces\ProductInterface;
use AcmeWidgetCo\Domain\Interfaces\TotalInterface;
use AcmeWidgetCo\Infrastructure\Config\Config;
use Brick\Money\Context\CustomContext;
use Brick\Money\Money;

class Total implements TotalInterface
{
    /**
     * @var array<Money>
     */
    private array $totals = [];

    private Money $grandTotal;

    public function __construct(private readonly string $currency = Config::DEFAULT_CURRENCY)
    {
        $this->grandTotal = Money::zero($this->currency, new CustomContext(ProductInterface::PRICE_SCALE));
    }

    public function setTotal(string $type, Money $total): void
    {
        $this->totals[$type] = $total;
    }

    public function getGrandTotal(): Money
    {
        return $this->grandTotal;
    }

    public function setGrandTotal(Money $grandTotal): void
    {
        $this->grandTotal = $grandTotal;
    }

    public function getTotal(string $type): Money
    {
        return $this->totals[$type] ??
            Money::zero($this->currency, new CustomContext(ProductInterface::PRICE_SCALE));
    }
}
