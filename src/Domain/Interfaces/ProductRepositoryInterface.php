<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Interfaces;

interface ProductRepositoryInterface
{

    /**
     * @return ProductInterface[]
     */
    public function getList(): array;

    /**
     * @param string $code
     * @return ProductInterface
     */
    public function getByCode(string $code): ProductInterface;

}
