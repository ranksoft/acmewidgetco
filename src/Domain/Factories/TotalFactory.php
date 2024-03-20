<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Factories;

use AcmeWidgetCo\Domain\Entities\Total;
use AcmeWidgetCo\Domain\Interfaces\TotalFactoryInterface;
use AcmeWidgetCo\Domain\Interfaces\TotalInterface;

class TotalFactory implements TotalFactoryInterface
{
    /**
     * @inheritdoc
     */
    public function create(string $currency): TotalInterface
    {
        return new Total($currency);
    }
}
