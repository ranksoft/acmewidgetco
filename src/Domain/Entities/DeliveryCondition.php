<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Entities;

use AcmeWidgetCo\Domain\Interfaces\DeliveryConditionInterface;
use Brick\Money\Money;

class DeliveryCondition implements DeliveryConditionInterface
{
    /**
     * @param string $condition
     * @param Money $value
     * @param Money $cost
     * @param int $priority
     */
    public function __construct(
        private string $condition,
        private Money  $value,
        private Money  $cost,
        private int    $priority = 0
    )
    {
    }

    /**
     * @inheritdoc
     */
    public function setCondition(string $condition): void
    {
        $this->condition = $condition;
    }

    /**
     * @inheritdoc
     */
    public function getCondition(): string
    {
        return $this->condition;
    }

    /**
     * @inheritdoc
     */
    public function getValue(): Money
    {
        return $this->value;
    }

    /**
     * @inheritdoc
     */
    public function setValue(Money $value): void
    {
        $this->value = $value;
    }

    /**
     * @inheritdoc
     */
    public function getCost(): Money
    {
        return $this->cost;
    }

    /**
     * @inheritdoc
     */
    public function setCost(Money $cost): void
    {
        $this->cost = $cost;
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
