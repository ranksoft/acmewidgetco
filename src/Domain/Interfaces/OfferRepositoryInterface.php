<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Interfaces;

interface OfferRepositoryInterface
{
    /**
     * @return OfferInterface[]
     */
    public function getList(): array;
}
