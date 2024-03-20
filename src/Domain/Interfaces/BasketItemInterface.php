<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Interfaces;

use Brick\Money\Money;

interface BasketItemInterface
{
    /**
     * @return ProductInterface
     */
    public function getProduct(): ProductInterface;

    /**
     * @return int
     */
    public function getQuantity(): int;

    /**
     * @param int $quantity
     * @return void
     */
    public function setQuantity(int $quantity): void;

    /**
     * @param DiscountInterface $discount
     * @return void
     */
    public function setDiscount(DiscountInterface $discount): void;

    /**
     * @return DiscountInterface
     */
    public function getDiscount(): DiscountInterface;

    /**
     * @return Money
     */
    public function getPrice(): Money;

    /**
     * @return Money
     */
    public function getPriceWithDiscount(): Money;
}
