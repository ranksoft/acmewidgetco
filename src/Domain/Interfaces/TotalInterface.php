<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Interfaces;

use Brick\Money\Money;

interface TotalInterface
{
    public function getTotal(string $type): Money;

    public function setTotal(string $type, Money $total): void;

    public function getGrandTotal(): Money;

    public function setGrandTotal(Money $grandTotal): void;
}
