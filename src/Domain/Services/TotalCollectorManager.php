<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Services;

use AcmeWidgetCo\Domain\Interfaces\BasketInterface;
use AcmeWidgetCo\Domain\Interfaces\TotalCollectorManagerInterface;
use AcmeWidgetCo\Domain\Interfaces\TotalRepositoryInterface;

class TotalCollectorManager implements TotalCollectorManagerInterface
{
    /**
     * @param TotalRepositoryInterface $configRepository
     * @param array<string> $totalTypes
     */
    public function __construct(
        private readonly TotalRepositoryInterface $configRepository,
        private readonly array $totalTypes
    )
    {
    }

    /**
     * @inheritdoc
     */
    public function collectTotals(
        BasketInterface $basket,
        string          $type = ''
    ): void
    {
        $collectors = [];
        if ($type) {
            $collectors = $this->configRepository->getTotalCollectorsByType($type);
        }

        if (!$type) {
            foreach ($this->totalTypes as $totalType) {
                $collectors = array_merge(
                    $collectors,
                    $this->configRepository->getTotalCollectorsByType($totalType)
                );
            }
        }

        usort($collectors, function ($first, $second) {
            return $first->getPriority() <=> $second->getPriority();
        });

        foreach ($collectors as $collector) {
            $collector->clearTotal($basket);
            $collector->collect($basket);
        }
    }
}
