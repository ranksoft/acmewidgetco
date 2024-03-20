<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Entities;

use AcmeWidgetCo\Domain\Interfaces\BasketInterface;
use AcmeWidgetCo\Domain\Interfaces\BasketItemFactoryInterface;
use AcmeWidgetCo\Domain\Interfaces\BasketItemInterface;
use AcmeWidgetCo\Domain\Interfaces\ProductRepositoryInterface;
use AcmeWidgetCo\Domain\Interfaces\TotalCollectorManagerInterface;
use AcmeWidgetCo\Domain\Interfaces\TotalFactoryInterface;
use AcmeWidgetCo\Domain\Interfaces\TotalInterface;
use AcmeWidgetCo\Infrastructure\Config\Config;
use Exception;
use Psr\Log\LoggerInterface;

class Basket implements BasketInterface
{

    /**
     * @var BasketItemInterface[]
     */
    private array $items = [];

    private TotalInterface $total;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param BasketItemFactoryInterface $basketItemFactory
     * @param TotalCollectorManagerInterface $totalCollectorManager
     * @param TotalFactoryInterface $totalFactory
     * @param LoggerInterface $logger
     * @param string $currency
     */
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly BasketItemFactoryInterface $basketItemFactory,
        private readonly TotalCollectorManagerInterface $totalCollectorManager,
        private readonly TotalFactoryInterface $totalFactory,
        private readonly LoggerInterface $logger,
        private readonly string $currency = Config::DEFAULT_CURRENCY
    )
    {
        $this->total = $this->totalFactory->create($this->currency);
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function add(string $productCode): void
    {
        try {
            $product = $this->productRepository->getByCode($productCode);
            if (isset($this->items[$productCode])) {
                $quantity = $this->items[$productCode]->getQuantity();
                $this->items[$productCode]->setQuantity(++$quantity);
            }

            if (!isset($this->items[$productCode])) {
                $this->items[$productCode] = $this->basketItemFactory->create($product, 1, $this->currency);
            }
            $this->totalCollectorManager->collectTotals($this);
        } catch (Exception $exception) {
            $this->logger->error(
                $exception->getMessage(),
                ['trace' => $exception->getTrace()]
            );
            throw $exception;
        }
    }

    /**
     * @inheritdoc
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @inheritdoc
     */
    public function getTotal(): TotalInterface
    {
        return $this->total;
    }
}
