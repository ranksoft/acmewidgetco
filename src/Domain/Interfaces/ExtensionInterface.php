<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Interfaces;

interface ExtensionInterface
{
    /**
     * @param array<mixed> $data
     * @return void
     */
    public function setExtensionData(array $data): void;

    /**
     * @return array<mixed>
     */
    public function getExtensionData(): array;
}
