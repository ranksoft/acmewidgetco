<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Interfaces;

use AcmeWidgetCo\Infrastructure\Config\Config;

interface BasketItemFactoryInterface
{
    /**
     * @param ProductInterface $product
     * @param int $quantity
     * @param string $currency
     * @return BasketItemInterface
     */
    public function create(
        ProductInterface $product,
        int              $quantity = 1,
        string           $currency = Config::DEFAULT_CURRENCY
    ): BasketItemInterface;
}
