<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Interfaces;

interface TotalFactoryInterface
{

    /**
     * @param string $currency
     * @return TotalInterface
     */
    public function create(string $currency): TotalInterface;
}
