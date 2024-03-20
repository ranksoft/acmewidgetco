<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Interfaces;

interface DeliveryConditionPriorityCalculatorInterface
{
    /**
     * @param array<array{'condition': string, 'priority': int}> $conditions
     * @return void
     */
    public function calculate(array &$conditions): void;
}
