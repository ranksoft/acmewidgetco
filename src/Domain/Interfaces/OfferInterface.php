<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Interfaces;

interface OfferInterface
{

    /**
     * @param BasketInterface $basket
     * @return void
     */
    public function apply(BasketInterface $basket): void;
}
