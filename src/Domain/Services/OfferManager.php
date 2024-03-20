<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Services;

use AcmeWidgetCo\Domain\Interfaces\BasketInterface;
use AcmeWidgetCo\Domain\Interfaces\OfferInterface;
use AcmeWidgetCo\Domain\Interfaces\OfferManagerInterface;
use AcmeWidgetCo\Domain\Interfaces\OfferRepositoryInterface;

class OfferManager implements OfferManagerInterface
{
    /**
     * @var OfferInterface[]
     */
    private array $strategies;

    /**
     * @param OfferRepositoryInterface $repository
     */
    public function __construct(OfferRepositoryInterface $repository)
    {
        $this->strategies = $repository->getList();
    }

    /**
     * @inheritdoc
     */
    public function applyOffers(BasketInterface $basket): void
    {
        foreach ($this->strategies as $strategy) {
            $strategy->apply($basket);
        }
    }
}
