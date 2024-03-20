<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Factories;

use AcmeWidgetCo\Domain\Entities\Product;
use AcmeWidgetCo\Domain\Interfaces\ProductFactoryInterface;
use AcmeWidgetCo\Domain\Interfaces\ProductInterface;
use Brick\Math\Exception\MathException;
use Brick\Math\RoundingMode;
use Brick\Money\Context\CustomContext;
use Brick\Money\Currency;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;

class ProductFactory implements ProductFactoryInterface
{
    /**
     * @inheritdoc
     * @throws MathException
     * @throws UnknownCurrencyException
     */
    public function create(string $code, string $name, string $price, string $currency): ProductInterface
    {
        $money = Money::of(
            $price,
            Currency::of($currency),
            new CustomContext(ProductInterface::PRICE_SCALE),
            RoundingMode::UP
        );

        return new Product($code, $name, $money);
    }
}
