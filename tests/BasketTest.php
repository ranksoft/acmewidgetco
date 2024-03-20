<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Tests;

use AcmeWidgetCo\Domain\Entities\Basket;
use AcmeWidgetCo\Domain\Entities\Product;
use AcmeWidgetCo\Domain\Factories\BasketItemFactory;
use AcmeWidgetCo\Domain\Factories\TotalFactory;
use AcmeWidgetCo\Domain\Interfaces\ProductInterface;
use AcmeWidgetCo\Domain\Interfaces\ProductRepositoryInterface;
use AcmeWidgetCo\Domain\Services\TotalCollectorManager;
use AcmeWidgetCo\Infrastructure\Config\Config;
use AcmeWidgetCo\Infrastructure\DI\DIContainer;
use AcmeWidgetCo\Infrastructure\Persistence\Repositories\ConfigTotalRepository;
use Brick\Math\Exception\MathException;
use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Money\Context\CustomContext;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class BasketTest extends TestCase
{
    private Basket $basket;
    private Product $productB01;
    private Product $productG01;
    private Product $productR01;
    private MockObject|ProductRepositoryInterface $productRepositoryMock;
    private BasketItemFactory $basketItemFactory;
    private TotalFactory $totalFactory;
    private TotalCollectorManager $totalCollectorManager;
    private MockObject|LoggerInterface $loggerMock;

    /**
     * @throws UnknownCurrencyException
     * @throws RoundingNecessaryException
     * @throws MathException
     * @throws NumberFormatException
     */
    protected function setUp(): void
    {
        $this->productB01 = new Product('B01',
            'Blue Widget',
            Money::of(7.95, 'USD', new CustomContext(ProductInterface::PRICE_SCALE))
        );
        $this->productG01 = new Product(
            'G01',
            'Green Widget',
            Money::of(24.95, 'USD', new CustomContext(ProductInterface::PRICE_SCALE))
        );
        $this->productR01 = new Product(
            'R01',
            'Red Widget',
            Money::of(32.95, 'USD', new CustomContext(ProductInterface::PRICE_SCALE))
        );
        $this->productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);
        $this->productRepositoryMock->method('getByCode')
            ->will($this->returnValueMap([
                ['B01', $this->productB01],
                ['G01', $this->productG01],
                ['R01', $this->productR01],
            ]));
        $this->productRepositoryMock->method('getList')
            ->willReturn([
                'B01' => $this->productB01,
                'G01' => $this->productG01,
                'R01' => $this->productR01,
            ]);
        $this->basketItemFactory = new BasketItemFactory();
        $this->totalFactory = new TotalFactory();
        $totalTypes = ['subtotal', 'discount', 'delivery'];
        $this->totalCollectorManager = new TotalCollectorManager(
            new ConfigTotalRepository(new DIContainer()),
            $totalTypes
        );
        $this->loggerMock = $this->createMock(LoggerInterface::class);
    }

    /**
     * @return void
     */
    private function resetBasket(): void
    {
        $this->basket = new Basket(
            $this->productRepositoryMock,
            $this->basketItemFactory,
            $this->totalCollectorManager,
            $this->totalFactory,
            $this->loggerMock,
            Config::DEFAULT_CURRENCY
        );
    }

    /**
     * @throws MathException
     * @throws NumberFormatException
     * @throws UnknownCurrencyException
     * @throws RoundingNecessaryException
     */
    public function testAddProductsAndCalculateTotal(): void
    {
        $this->resetBasket();
        // B01, G01
        $this->basket->add('B01');
        $this->basket->add('G01');
        $this->assertEquals(
            Money::of(37.85, 'USD', new CustomContext(ProductInterface::PRICE_SCALE)),
            $this->basket->getTotal()->getGrandTotal()
        );

        $this->resetBasket();
        // R01, R01
        $this->basket->add('R01');
        $this->basket->add('R01');
        $this->assertEquals(
            Money::of(54.375, 'USD', new CustomContext(ProductInterface::PRICE_SCALE)),
            $this->basket->getTotal()->getGrandTotal()
        );

        $this->resetBasket();
        // R01, G01
        $this->basket->add('R01');
        $this->basket->add('G01');
        $this->assertEquals(
            Money::of(60.85, 'USD', new CustomContext(ProductInterface::PRICE_SCALE)),
            $this->basket->getTotal()->getGrandTotal()
        );

        $this->resetBasket();
        // B01, B01, R01, R01, R01
        $this->basket->add('B01');
        $this->basket->add('B01');
        $this->basket->add('R01');
        $this->basket->add('R01');
        $this->basket->add('R01');
        $this->assertEquals(
            Money::of(98.275, 'USD', new CustomContext(ProductInterface::PRICE_SCALE)),
            $this->basket->getTotal()->getGrandTotal()
        );
    }
}