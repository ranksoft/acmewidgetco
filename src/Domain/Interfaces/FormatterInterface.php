<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Interfaces;

use Brick\Math\RoundingMode;
use Brick\Money\Money;

interface FormatterInterface
{
    /**
     * @param Money $money
     * @param RoundingMode $roundingMode
     * @return string
     */
    public function format(Money $money, RoundingMode $roundingMode = RoundingMode::UNNECESSARY): string;
}