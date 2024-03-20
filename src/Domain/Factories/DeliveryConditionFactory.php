<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Factories;

use AcmeWidgetCo\Domain\Entities\DeliveryCondition;
use AcmeWidgetCo\Domain\Interfaces\DeliveryConditionFactoryInterface;
use AcmeWidgetCo\Domain\Interfaces\DeliveryConditionInterface;
use AcmeWidgetCo\Domain\Interfaces\ProductInterface;
use AcmeWidgetCo\Infrastructure\Config\Config;
use Brick\Math\Exception\MathException;
use Brick\Math\RoundingMode;
use Brick\Money\Context\CustomContext;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;

class DeliveryConditionFactory implements DeliveryConditionFactoryInterface
{
    /**
     * @inheritdoc
     * @throws MathException
     * @throws UnknownCurrencyException
     */
    public function create(
        string $condition,
        string $value,
        string $cost,
        int    $priority = 0,
        string $currency = Config::DEFAULT_CURRENCY
    ): DeliveryConditionInterface
    {
        $value = Money::of(
            $value,
            $currency,
            new CustomContext(ProductInterface::PRICE_SCALE),
            RoundingMode::UP
        );
        $cost = Money::of(
            $cost,
            $currency,
            new CustomContext(ProductInterface::PRICE_SCALE),
            RoundingMode::UP
        );
        return new  DeliveryCondition(
            $condition,
            $value,
            $cost,
            $priority
        );
    }
}
