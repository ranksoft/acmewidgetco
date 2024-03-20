<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Entities;

use AcmeWidgetCo\Domain\Interfaces\BasketItemInterface;
use AcmeWidgetCo\Domain\Interfaces\ProductInterface;
use AcmeWidgetCo\Infrastructure\Config\Config;
use Brick\Math\Exception\MathException;
use Brick\Money\Context\CustomContext;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Money;

class BasketItem implements BasketItemInterface
{
    /**
     * @var Money
     */
    private Money $price;

    /**
     * @var Money
     */
    private Money $discount;

    /**
     * @param ProductInterface $product
     * @param int $quantity
     * @param string $currency
     * @throws MathException
     */
    public function __construct(
        private     readonly ProductInterface $product,
        private int $quantity = 1,
        private     readonly string $currency = Config::DEFAULT_CURRENCY
    )
    {
        $this->setPrice(Money::zero($this->currency, new CustomContext(ProductInterface::PRICE_SCALE)));
        $this->setDiscount(Money::zero($this->currency, new CustomContext(ProductInterface::PRICE_SCALE)));
    }

    /**
     * @inheritdoc
     */
    public function getProduct(): ProductInterface
    {
        return $this->product;
    }

    /**
     * @inheritdoc
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @inheritdoc
     * @throws MathException
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
        $this->recalculatePrice();
    }

    /**
     * @inheritdoc
     * @throws MathException
     */
    public function setDiscount(Money $discount): void
    {
        $this->discount = $discount;
        $this->recalculatePrice();
    }

    /**
     * @inheritdoc
     */
    public function getDiscount(): Money
    {
        return $this->discount;
    }

    /**
     * @inheritdoc
     */
    public function getPrice(): Money
    {
        return $this->price;
    }

    /**
     * @inheritdoc
     * @throws MathException
     * @throws MoneyMismatchException
     */
    public function getPriceWithDiscount(): Money
    {
        $price = $this->price;
        if (!$this->discount->isZero()) {
            $price = $price->minus($this->discount);
        }
        return $price;
    }


    /**
     * @param Money $price
     * @return void
     */
    private function setPrice(Money $price): void
    {
        $this->price = $price;
    }

    /**
     * @return void
     * @throws MathException
     */
    private function recalculatePrice(): void
    {
        $this->price = $this->product->getPrice()->multipliedBy($this->quantity);
    }
}
