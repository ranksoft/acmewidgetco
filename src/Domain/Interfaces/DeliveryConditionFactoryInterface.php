<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Interfaces;

use AcmeWidgetCo\Infrastructure\Config\Config;

interface DeliveryConditionFactoryInterface
{
    /**
     * @param string $condition
     * @param string $value
     * @param string $cost
     * @param int $priority
     * @param string $currency
     * @return DeliveryConditionInterface
     */
    public function create(
        string $condition,
        string $value,
        string $cost,
        int    $priority = 0,
        string $currency = Config::DEFAULT_CURRENCY
    ): DeliveryConditionInterface;
}
