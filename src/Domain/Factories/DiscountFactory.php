<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Factories;

use AcmeWidgetCo\Domain\Entities\Discount;
use AcmeWidgetCo\Domain\Interfaces\DiscountInterface;
use AcmeWidgetCo\Domain\Interfaces\DiscountFactoryInterface;

class DiscountFactory implements DiscountFactoryInterface
{
    /**
     * @inheritdoc
     */
    public function create(string $currency): DiscountInterface
    {
        return new Discount($currency);
    }
}
