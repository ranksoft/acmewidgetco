<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Interfaces;

interface DiscountFactoryInterface
{

    /**
     * @param string $currency
     * @return DiscountInterface
     */
    public function create(string $currency): DiscountInterface;
}
