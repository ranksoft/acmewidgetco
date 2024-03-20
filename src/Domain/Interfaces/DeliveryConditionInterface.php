<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Interfaces;

use Brick\Money\Money;

interface DeliveryConditionInterface
{
    /**
     * @param string $condition
     * @return void
     */
    public function setCondition(string $condition): void;

    /**
     * @return string
     */
    public function getCondition(): string;

    /**
     * @return Money
     */
    public function getValue(): Money;

    /**
     * @param Money $value
     */
    public function setValue(Money $value): void;

    /**
     * @return Money
     */
    public function getCost(): Money;

    /**
     * @param Money $cost
     */
    public function setCost(Money $cost): void;

    /**
     * @return int
     */
    public function getPriority(): int;

    /**
     * @param int $priority
     */
    public function setPriority(int $priority): void;
}
