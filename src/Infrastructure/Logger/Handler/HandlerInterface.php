<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Infrastructure\Logger\Handler;

interface HandlerInterface
{
    public const DEFAULT_FORMAT = '%timestamp% [%level%]: %message%';

    /**
     * @param array<mixed> $vars
     * @return void
     */
    public function handle(array $vars): void;
}
