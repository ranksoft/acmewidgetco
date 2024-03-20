<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Interfaces;

interface BasketInterface
{
    /**
     * @param string $productCode
     * @return void
     */
    public function add(string $productCode): void;

    /**
     * @return BasketItemInterface[]
     */
    public function getItems(): array;

    /**
     * @return TotalInterface
     */
    public function getTotal(): TotalInterface;
}
