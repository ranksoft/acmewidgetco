<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Interfaces;

interface TotalRepositoryInterface
{

    /**
     * @param string $type
     * @return TotalCollectorInterface[]
     */
    public function getTotalCollectorsByType(string $type): array;
}
