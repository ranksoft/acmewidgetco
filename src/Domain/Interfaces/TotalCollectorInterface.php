<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Interfaces;

interface TotalCollectorInterface
{

    /**
     * @param BasketInterface $basket
     * @return void
     */
    public function collect(BasketInterface $basket): void;

    /**
     * @param BasketInterface $basket
     * @return void
     */
    public function clearTotal(BasketInterface $basket): void;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param TotalInterface $total
     * @return void
     */
    public function updateGrandTotal(TotalInterface $total): void;

    /**
     * @return int
     */
    public function getPriority(): int;

    /**
     * @param int $priority
     */
    public function setPriority(int $priority): void;
}
