<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Interfaces;

use Brick\Money\Money;

interface ProductInterface
{

    public final const PRICE_SCALE = 10;

    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return Money
     */
    public function getPrice(): Money;
}
