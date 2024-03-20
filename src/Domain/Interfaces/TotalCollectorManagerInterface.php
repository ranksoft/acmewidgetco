<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Interfaces;

interface TotalCollectorManagerInterface
{
    /**
     * @param BasketInterface $basket
     * @param string $type
     * @return void
     */
    public function collectTotals(
        BasketInterface $basket,
        string          $type = ''
    ): void;
}
