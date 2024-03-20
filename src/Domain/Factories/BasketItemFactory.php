<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Factories;

use AcmeWidgetCo\Domain\Entities\BasketItem;
use AcmeWidgetCo\Domain\Interfaces\BasketItemFactoryInterface;
use AcmeWidgetCo\Domain\Interfaces\BasketItemInterface;
use AcmeWidgetCo\Domain\Interfaces\DiscountFactoryInterface;
use AcmeWidgetCo\Domain\Interfaces\ProductInterface;
use AcmeWidgetCo\Infrastructure\Config\Config;

class BasketItemFactory implements BasketItemFactoryInterface
{
    public function __construct(private readonly DiscountFactoryInterface $discountFactory)
    {
    }

    /**
     * @inheritdoc
     */
    public function create(
        ProductInterface $product,
        int              $quantity = 1,
        string           $currency = Config::DEFAULT_CURRENCY
    ): BasketItemInterface
    {
        return new BasketItem($product, $this->discountFactory->create($currency), $quantity, $currency);
    }
}
