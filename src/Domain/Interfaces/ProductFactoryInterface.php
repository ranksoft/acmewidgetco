<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Interfaces;

interface ProductFactoryInterface
{

    /**
     * @param string $code
     * @param string $name
     * @param string $price
     * @param string $currency
     * @return ProductInterface
     */
    public function create(string $code, string $name, string $price, string $currency): ProductInterface;
}
