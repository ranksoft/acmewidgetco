<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Interfaces;

interface OfferManagerInterface
{

    /**
     * @param BasketInterface $basket
     * @return void
     */
    public function applyOffers(BasketInterface $basket): void;
}
