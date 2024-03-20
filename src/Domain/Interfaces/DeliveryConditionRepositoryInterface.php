<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Interfaces;

interface DeliveryConditionRepositoryInterface
{

    /**
     * @return DeliveryConditionInterface[]
     */
    public function getList(): array;
}
