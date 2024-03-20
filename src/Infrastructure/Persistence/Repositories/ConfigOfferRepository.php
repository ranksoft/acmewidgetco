<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Infrastructure\Persistence\Repositories;

use AcmeWidgetCo\Domain\Interfaces\ExtensionInterface;
use AcmeWidgetCo\Domain\Interfaces\OfferInterface;
use AcmeWidgetCo\Domain\Interfaces\OfferRepositoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ConfigOfferRepository implements OfferRepositoryInterface
{
    /**
     * @var string
     */
    private const PATH_TO_SOURCE_CONFIG_FILE = __DIR__ . '/../../../../config/offers.php';

    /**
     * @var array<mixed>
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
     * @return array<array{'class': string, 'priority': int, 'config': array<array{'product_code': string}>}>
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
     * Returns an array of offer strategies, sorted by priority.
     *
     * @return OfferInterface[]
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getList(): array
    {
        $strategies = [];
        foreach ($this->config as $strategyConfig) {
            if (!is_array($strategyConfig) || !isset($strategyConfig['class'])) {
                continue;
            }

            if (!class_exists($strategyConfig['class'])) {
                throw new \InvalidArgumentException("Strategy class does not exist: {$strategyConfig['class']}");
            }

            $strategy = $this->container->get($strategyConfig['class']);
            if ($strategy instanceof ExtensionInterface) {
                $strategy->setExtensionData($strategyConfig['config'] ?? []);
            }

            if (!$strategy instanceof OfferInterface) {
                throw new \InvalidArgumentException("Strategy must implement OfferStrategyInterface: {$strategyConfig['class']}");
            }

            $strategies[] = [
                'strategy' => $strategy,
                'priority' => $strategyConfig['priority'] ?? 0,
            ];
        }

        usort($strategies, function ($first, $second) {
            return $first['priority'] <=> $second['priority'];
        });

        $sortedStrategies = array_map(function ($entry) {
            return $entry['strategy'];
        }, $strategies);

        return $sortedStrategies;
    }
}
