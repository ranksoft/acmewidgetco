<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Entities;

use AcmeWidgetCo\Domain\Interfaces\ProductInterface;
use Brick\Money\Money;

class Product implements ProductInterface
{

    /**
     * @param string $code
     * @param string $name
     * @param Money $price
     */
    public function __construct(
        private string $code,
        private string $name,
        private Money $price
    )
    {
    }

    /**
     * @inheritdoc
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function getPrice(): Money
    {
        return $this->price;
    }
}
