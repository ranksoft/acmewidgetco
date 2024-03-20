<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Infrastructure\Persistence\Repositories;

use AcmeWidgetCo\Domain\Interfaces\TotalCollectorInterface;
use AcmeWidgetCo\Domain\Interfaces\TotalRepositoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ConfigTotalRepository implements TotalRepositoryInterface
{
    /**
     * @var string
     */
    private const PATH_TO_SOURCE_CONFIG_FILE = __DIR__ . '/../../../../config/totals.php';

    /**
     * @var array<array{collectors: list<string>, priority: int}>
     */
    private array $config;

    /**
     * @param ContainerInterface $container
     * @param string $configFilePath
     */
    public function __construct(
        private readonly ContainerInterface $container,
        string  $configFilePath = self::PATH_TO_SOURCE_CONFIG_FILE
    )
    {
        $this->config = $this->loadConfig($configFilePath);
    }

    /**
     * @param string $configFilePath
     * @return array<array{collectors: list<string>, priority: int}> The loaded configuration.
     */
    private function loadConfig(string $configFilePath): array
    {
        if (!file_exists($configFilePath)) {
            throw new \InvalidArgumentException("Configuration file not found: $configFilePath");
        }

        $configData = include $configFilePath;
        if (!is_array($configData)) {
            throw new \InvalidArgumentException("Invalid configuration format in $configFilePath");
        }

        return $configData;
    }

    /**
     * @param string $type
     * @return TotalCollectorInterface[]
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getTotalCollectorsByType(string $type): array
    {
        if (!isset($this->config[$type])) {
            throw new \InvalidArgumentException("Unknown total type: {$type}");
        }

        $collectors = [];
        foreach ($this->config[$type]['collectors'] as $collectorClass) {
            if (!is_string($collectorClass) || !class_exists($collectorClass)) {
                throw new \InvalidArgumentException("Invalid or non-existent collector class: " . gettype($collectorClass));
            }

            if (!class_exists($collectorClass)) {
                throw new \InvalidArgumentException("Collector class does not exist: $collectorClass");
            }

            $collector = $this->container->get($collectorClass);
            if (!$collector instanceof TotalCollectorInterface) {
                throw new \InvalidArgumentException("Collector must implement TotalCollectorInterface: $collectorClass");
            }

            if ($collector->getType() !== $type) {
                throw new \InvalidArgumentException("Collector type does not match with class: {$collectorClass}");
            }

            if (!$this->config[$type]['priority']) {
                throw new \InvalidArgumentException("Priority does not configured for collector: $collectorClass");
            }

            $collector->setPriority($this->config[$type]['priority']);
            $collectors[] = $collector;
        }

        return $collectors;
    }
}
