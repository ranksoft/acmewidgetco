<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Services;

use AcmeWidgetCo\Domain\Enums\DeliveryConditionType;
use AcmeWidgetCo\Domain\Interfaces\DeliveryConditionPriorityCalculatorInterface;

class DeliveryConditionPriorityCalculator implements DeliveryConditionPriorityCalculatorInterface
{
    /**
     * @param array<array{'condition': string, 'priority': int}> $conditions
     * @return void
     */
    public function calculate(array &$conditions): void
    {
        foreach ($conditions as &$condition) {
            $conditionType = DeliveryConditionType::from($condition['condition']);
            $condition['priority'] = $conditionType->getPriority();
        }
    }
}
