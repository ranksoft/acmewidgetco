<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\TotalCollectors;

use AcmeWidgetCo\Domain\Interfaces\BasketInterface;
use AcmeWidgetCo\Domain\Interfaces\OfferManagerInterface;
use AcmeWidgetCo\Domain\Interfaces\ProductInterface;
use AcmeWidgetCo\Domain\Interfaces\TotalCollectorInterface;
use AcmeWidgetCo\Domain\Interfaces\TotalInterface;
use AcmeWidgetCo\Infrastructure\Config\Config;
use Brick\Math\Exception\MathException;
use Brick\Money\Context\CustomContext;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Money;

class DiscountCollector implements TotalCollectorInterface
{
    /**
     * @var int
     */
    private int $priority = 0;

    /**
     * @param OfferManagerInterface $offerManager
     * @param string $totalType
     * @param string $currency
     */
    public function __construct(
        private readonly OfferManagerInterface $offerManager,
        private readonly string $totalType,
        private readonly string $currency = Config::DEFAULT_CURRENCY
    )
    {
    }

    /**
     * @inheritdoc
     * @throws MathException
     * @throws MoneyMismatchException
     */
    public function collect(BasketInterface $basket): void
    {
        $this->offerManager->applyOffers($basket);
        $total = $basket->getTotal();
        foreach ($basket->getItems() as $item) {
            if (!$item->getDiscount()->isZero()) {
                $total->setTotal(
                    $this->getType(),
                    $total->getTotal($this->getType())->plus($item->getDiscount())
                );
            }
        }
        $this->updateGrandTotal($basket->getTotal());
    }

    /**
     * @inheritdoc
     */
    public function updateGrandTotal(TotalInterface $total): void
    {
        $grandTotal = $total->getGrandTotal();
        $grandTotal = $grandTotal->minus($total->getTotal($this->getType()));
        $total->setGrandTotal($grandTotal);
    }

    /**
     * @inheritdoc
     */
    public function clearTotal(BasketInterface $basket): void
    {
        $basket->getTotal()->setTotal($this->getType(),
            Money::zero($this->currency, new CustomContext(ProductInterface::PRICE_SCALE))
        );
    }

    /**
     * @inheritdoc
     */
    public function getType(): string
    {
        return $this->totalType;
    }

    /**
     * @inheritdoc
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @inheritdoc
     */
    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }
}
