<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Interfaces;

use Brick\Money\Money;

interface FormatterInterface
{
    /**
     * @param Money $money
     * @return string
     */
    public function format(Money $money): string;
}