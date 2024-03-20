<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Infrastructure\Persistence\Repositories;

use AcmeWidgetCo\Domain\Interfaces\DeliveryConditionFactoryInterface;
use AcmeWidgetCo\Domain\Interfaces\DeliveryConditionPriorityCalculatorInterface;
use AcmeWidgetCo\Domain\Interfaces\DeliveryConditionRepositoryInterface;
use AcmeWidgetCo\Infrastructure\Config\Config;

class ConfigDeliveryConditionRepository implements DeliveryConditionRepositoryInterface
{
    /**
     * @var string
     */
    private const PATH_TO_SOURCE_CONFIG_FILE = __DIR__ . '/../../../../config/delivery_conditions.php';

    /**
     * @param DeliveryConditionPriorityCalculatorInterface $priorityCalculator
     * @param DeliveryConditionFactoryInterface $conditionFactory
     * @param string $currency
     */
    public function __construct(
        private readonly DeliveryConditionPriorityCalculatorInterface $priorityCalculator,
        private readonly DeliveryConditionFactoryInterface $conditionFactory,
        private readonly string $currency = Config::DEFAULT_CURRENCY
    )
    {
    }

    /**
     * @inheritdoc
     */
    public function getList(): array
    {
        $conditions = [];
        $conditionsConfig = require self::PATH_TO_SOURCE_CONFIG_FILE;

        $this->priorityCalculator->calculate($conditionsConfig);

        usort($conditionsConfig, fn($first, $second) => $second['priority'] <=> $first['priority']);

        foreach ($conditionsConfig as $conditionConfig) {
            $condition = $this->conditionFactory->create(
                $conditionConfig['condition'],
                (string)$conditionConfig['value'],
                (string)$conditionConfig['cost'],
                $conditionConfig['priority'],
                $this->currency
            );
            $conditions[] = $condition;
        }

        return $conditions;
    }
}
