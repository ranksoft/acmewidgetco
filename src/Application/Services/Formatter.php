<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Application\Services;

use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use AcmeWidgetCo\Domain\Interfaces\FormatterInterface;

class Formatter implements FormatterInterface
{
    /**
     * @inheritdoc
     * @throws RoundingNecessaryException
     */
    public function format(Money $money): string
    {
        $fractionalPart = $money->getAmount()->getFractionalPart();
        if (!(int)$fractionalPart) {
            return $money->getAmount()->getIntegralPart();
        }

        return (string)$money->getAmount()->toScale(2, RoundingMode::UP);
    }
}
