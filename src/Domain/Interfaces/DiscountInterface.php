<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Interfaces;

use Brick\Money\Money;

interface DiscountInterface
{
    /**
     * @param string $type
     * @param Money $discount
     */
    public function setDiscount(string $type, Money $discount): void;

    /**
     * @param string $type
     * @return Money
     */
    public function getDiscount(string $type): Money;

    /**
     * @return Money
     */
    public function getTotalDiscount(): Money;
}
