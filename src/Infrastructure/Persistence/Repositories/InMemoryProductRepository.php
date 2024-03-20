<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Infrastructure\Persistence\Repositories;

use AcmeWidgetCo\Domain\Interfaces\ProductFactoryInterface;
use AcmeWidgetCo\Domain\Interfaces\ProductInterface;
use AcmeWidgetCo\Domain\Interfaces\ProductRepositoryInterface;
use OutOfBoundsException;
use Psr\Log\LoggerInterface;
use JsonException;
use RuntimeException;

class InMemoryProductRepository implements ProductRepositoryInterface
{
    /**
     * @var string
     */
    private const PATH_TO_SOURCE_FILE = __DIR__ . '/../../../../var/data/products.txt';

    /**
     * @param ProductFactoryInterface $productFactory
     * @param LoggerInterface $logger
     * @param string $sourceFile
     * @param ProductInterface[] $products
     */
    public function __construct(
        private       readonly ProductFactoryInterface $productFactory,
        private       readonly LoggerInterface $logger,
        private       readonly string $sourceFile = self::PATH_TO_SOURCE_FILE,
        private array $products = []
    )
    {
    }

    /**
     * @param string $code
     * @return ProductInterface
     * @throws OutOfBoundsException
     * @throws RuntimeException
     */
    public function getByCode(string $code): ProductInterface
    {
        $product = $this->getList()[$code] ?? null;
        if (!$product) {
            throw new OutOfBoundsException("Product with code: {$code} not found");
        }

        return $product;
    }

    /**
     * @return ProductInterface[]
     * @throws RuntimeException|JsonException
     */
    public function getList(): array
    {
        try {
            foreach ($this->getLines() as $line) {
                $data = json_decode($line, false, 512, JSON_THROW_ON_ERROR);
                if (!$data instanceof \stdClass || !isset($data->code, $data->name, $data->price, $data->currency)) {
                    throw new RuntimeException("Invalid or incomplete product data.");
                }

                $this->products[$data->code] = $this->productFactory->create(
                    $data->code,
                    $data->name,
                    $data->price,
                    $data->currency
                );
            }
        } catch (RuntimeException|JsonException $exception) {
            $this->logger->error($exception->getMessage());
            throw $exception;
        }

        return $this->products;
    }

    /**
     * @return iterable<string>
     * @throws RuntimeException
     */
    private function getLines(): iterable
    {
        $file = fopen($this->sourceFile, 'rb');
        if (!$file) throw new RuntimeException("Could not open the file: {$this->sourceFile}");

        try {
            while (($line = fgets($file)) !== false) {
                yield $line;
            }
        } finally {
            fclose($file);
        }
    }
}
