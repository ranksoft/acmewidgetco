<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Entities;

use AcmeWidgetCo\Domain\Interfaces\BasketItemInterface;
use AcmeWidgetCo\Domain\Interfaces\DiscountInterface;
use AcmeWidgetCo\Domain\Interfaces\ProductInterface;
use AcmeWidgetCo\Infrastructure\Config\Config;
use Brick\Math\Exception\MathException;
use Brick\Math\RoundingMode;
use Brick\Money\Context\CustomContext;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;

class BasketItem implements BasketItemInterface
{
    /**
     * @var Money
     */
    private Money $price;

    /**
     * @param ProductInterface $product
     * @param DiscountInterface $discount
     * @param int $quantity
     * @param string $currency
     */
    public function __construct(
        private                   readonly ProductInterface $product,
        private DiscountInterface $discount,
        private int               $quantity = 1,
        private                   readonly string $currency = Config::DEFAULT_CURRENCY
    )
    {
        $this->setPrice(Money::zero($this->currency, new CustomContext(ProductInterface::PRICE_SCALE)));
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
     */
    public function setDiscount(DiscountInterface $discount): void
    {
        $this->discount = $discount;
    }

    /**
     * @inheritdoc
     */
    public function getDiscount(): DiscountInterface
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
     * @throws MoneyMismatchException|UnknownCurrencyException
     */
    public function getPriceWithDiscount(): Money
    {
        $this->recalculatePrice();
        $price = $this->price;
        if (!$this->discount->getTotalDiscount()->isZero()) {
            $price = $price->minus($this->discount->getTotalDiscount());
        }
        return Money::of(
            $price->getAmount()->toScale(2, RoundingMode::DOWN),
            $price->getCurrency(),
            new CustomContext(ProductInterface::PRICE_SCALE),
            RoundingMode::UP
        );
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
