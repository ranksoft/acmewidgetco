<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\TotalCollectors;

use AcmeWidgetCo\Domain\Enums\DeliveryConditionType;
use AcmeWidgetCo\Domain\Interfaces\BasketInterface;
use AcmeWidgetCo\Domain\Interfaces\DeliveryConditionInterface;
use AcmeWidgetCo\Domain\Interfaces\DeliveryConditionRepositoryInterface;
use AcmeWidgetCo\Domain\Interfaces\ProductInterface;
use AcmeWidgetCo\Domain\Interfaces\TotalCollectorInterface;
use AcmeWidgetCo\Domain\Interfaces\TotalInterface;
use AcmeWidgetCo\Infrastructure\Config\Config;
use Brick\Math\Exception\MathException;
use Brick\Money\Context\CustomContext;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Money;

class DeliveryCollector implements TotalCollectorInterface
{
    /**
     * @var int
     */
    private int $priority = 0;

    /**
     * @param DeliveryConditionRepositoryInterface $repository
     * @param string $totalType
     * @param string $currency
     */
    public function __construct(
        private readonly DeliveryConditionRepositoryInterface $repository,
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
        $deliveryCost = $this->getDeliveryByBasket($basket);
        $basket->getTotal()->setTotal($this->getType(), $deliveryCost);
        $this->updateGrandTotal($basket->getTotal());
    }

    /**
     * @inheritdoc
     */
    public function updateGrandTotal(TotalInterface $total): void
    {
        $grandTotal = $total->getGrandTotal();
        $grandTotal = $grandTotal->plus($total->getTotal($this->getType()));
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

    /**
     * @param BasketInterface $basket
     * @return Money
     * @throws MathException
     * @throws MoneyMismatchException
     */
    private function getDeliveryByBasket(BasketInterface $basket): Money
    {
        $conditions = $this->repository->getList();
        $total = $basket->getTotal()->getGrandTotal();
        foreach ($conditions as $condition) {
            if ($this->evaluateCondition($total, $condition)) {
                return $condition->getCost();
            }
        }

        return Money::zero($this->currency, new CustomContext(ProductInterface::PRICE_SCALE));
    }

    /**
     * @param Money $subtotal
     * @param DeliveryConditionInterface $condition
     * @return bool
     * @throws MathException
     * @throws MoneyMismatchException
     */
    private function evaluateCondition(Money $subtotal, DeliveryConditionInterface $condition): bool
    {
        $conditionType = DeliveryConditionType::from($condition->getCondition());
        return match ($conditionType) {
            DeliveryConditionType::LessThan => $subtotal->isLessThan($condition->getValue()),
            DeliveryConditionType::LessThanOrEqual => $subtotal->isLessThanOrEqualTo($condition->getValue()),
            DeliveryConditionType::Equal => $subtotal->isEqualTo($condition->getValue()),
            DeliveryConditionType::GreaterThan => $subtotal->isGreaterThan($condition->getValue()),
            DeliveryConditionType::GreaterThanOrEqual => $subtotal->isGreaterThanOrEqualTo($condition->getValue()),
        };
    }
}
