<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Strategies\Offers;

use AcmeWidgetCo\Domain\Interfaces\BasketInterface;
use AcmeWidgetCo\Domain\Interfaces\ExtensionInterface;
use AcmeWidgetCo\Domain\Interfaces\OfferInterface;

class BuyOneGetOneHalfPrice implements OfferInterface, ExtensionInterface
{
    /**
     * @var string
     */
    const EXTENSION_DATA_PRODUCT_CODE_KEY = 'product_code';

    /**
     * @var array<mixed>
     */
    private array $extensionData = [];

    /**
     * @inheritdoc
     */
    public function apply(BasketInterface $basket): void
    {
        foreach ($basket->getItems() as $item) {
            $productCode = $this->getExtensionData()[self::EXTENSION_DATA_PRODUCT_CODE_KEY] ?? '';
            if ($item->getProduct()->getCode() === $productCode) {
                $quantity = $item->getQuantity();
                $discountQuantity = intdiv($quantity, 2);
                if ($discountQuantity > 0) {
                    $halfPrice = $item->getProduct()->getPrice()->dividedBy(2);
                    $totalDiscount = $halfPrice->multipliedBy($discountQuantity);
                    $item->setDiscount($totalDiscount);
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function setExtensionData(array $data): void
    {
        $this->extensionData = $data;
    }

    /**
     * @inheritdoc
     */
    public function getExtensionData(): array
    {
        return $this->extensionData;
    }
}
