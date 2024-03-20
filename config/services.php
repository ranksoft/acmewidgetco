<?php
// config/services.php

use AcmeWidgetCo\Application\Services\Formatter;
use AcmeWidgetCo\Domain\Entities\Basket;
use AcmeWidgetCo\Domain\Entities\BasketItem;
use AcmeWidgetCo\Domain\Entities\DeliveryCondition;
use AcmeWidgetCo\Domain\Entities\Discount;
use AcmeWidgetCo\Domain\Entities\Product;
use AcmeWidgetCo\Domain\Entities\Total;
use AcmeWidgetCo\Domain\Factories\BasketItemFactory;
use AcmeWidgetCo\Domain\Factories\DeliveryConditionFactory;
use AcmeWidgetCo\Domain\Factories\DiscountFactory;
use AcmeWidgetCo\Domain\Factories\ProductFactory;
use AcmeWidgetCo\Domain\Factories\TotalFactory;
use AcmeWidgetCo\Domain\Interfaces\BasketInterface;
use AcmeWidgetCo\Domain\Interfaces\BasketItemFactoryInterface;
use AcmeWidgetCo\Domain\Interfaces\BasketItemInterface;
use AcmeWidgetCo\Domain\Interfaces\DeliveryConditionFactoryInterface;
use AcmeWidgetCo\Domain\Interfaces\DeliveryConditionInterface;
use AcmeWidgetCo\Domain\Interfaces\DeliveryConditionPriorityCalculatorInterface;
use AcmeWidgetCo\Domain\Interfaces\DeliveryConditionRepositoryInterface;
use AcmeWidgetCo\Domain\Interfaces\DiscountFactoryInterface;
use AcmeWidgetCo\Domain\Interfaces\DiscountInterface;
use AcmeWidgetCo\Domain\Interfaces\FormatterInterface;
use AcmeWidgetCo\Domain\Interfaces\OfferManagerInterface;
use AcmeWidgetCo\Domain\Interfaces\OfferRepositoryInterface;
use AcmeWidgetCo\Domain\Interfaces\ProductFactoryInterface;
use AcmeWidgetCo\Domain\Interfaces\ProductInterface;
use AcmeWidgetCo\Domain\Interfaces\ProductRepositoryInterface;
use AcmeWidgetCo\Domain\Interfaces\TotalCollectorManagerInterface;
use AcmeWidgetCo\Domain\Interfaces\TotalFactoryInterface;
use AcmeWidgetCo\Domain\Interfaces\TotalInterface;
use AcmeWidgetCo\Domain\Interfaces\TotalRepositoryInterface;
use AcmeWidgetCo\Domain\Services\DeliveryConditionPriorityCalculator;
use AcmeWidgetCo\Domain\Services\OfferManager;
use AcmeWidgetCo\Domain\Services\TotalCollectorManager;
use AcmeWidgetCo\Domain\TotalCollectors\DeliveryCollector;
use AcmeWidgetCo\Domain\TotalCollectors\SubTotalCollector;
use AcmeWidgetCo\Infrastructure\Config\Config;
use AcmeWidgetCo\Infrastructure\DI\DIContainer;
use AcmeWidgetCo\Infrastructure\Logger\Handler\FileHandler;
use AcmeWidgetCo\Infrastructure\Logger\Handler\HandlerInterface;
use AcmeWidgetCo\Infrastructure\Logger\Logger;
use AcmeWidgetCo\Infrastructure\Persistence\Repositories\ConfigDeliveryConditionRepository;
use AcmeWidgetCo\Infrastructure\Persistence\Repositories\ConfigOfferRepository;
use AcmeWidgetCo\Infrastructure\Persistence\Repositories\ConfigTotalRepository;
use AcmeWidgetCo\Infrastructure\Persistence\Repositories\InMemoryProductRepository;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return [
    ContainerInterface::class => DIContainer::class,
    HandlerInterface::class => FileHandler::class,
    LoggerInterface::class => Logger::class,
    FormatterInterface::class => Formatter::class,
    ProductFactoryInterface::class => ProductFactory::class,
    ProductInterface::class => Product::class,
    TotalRepositoryInterface::class => ConfigTotalRepository::class,
    OfferRepositoryInterface::class => ConfigOfferRepository::class,
    DeliveryConditionInterface::class => DeliveryCondition::class,
    DeliveryConditionFactoryInterface::class => DeliveryConditionFactory::class,
    OfferManagerInterface::class => OfferManager::class,
    BasketItemFactoryInterface::class => BasketItemFactory::class,
    TotalFactoryInterface::class => TotalFactory::class,
    TotalInterface::class => Total::class,
    DiscountFactoryInterface::class => DiscountFactory::class,
    DiscountInterface::class => Discount::class,
    DeliveryConditionPriorityCalculatorInterface::class => DeliveryConditionPriorityCalculator::class,
    ProductRepositoryInterface::class => InMemoryProductRepository::class,
    Config::class => function () {
        return new Config();
    },
    TotalCollectorManagerInterface::class => [
        'class' => TotalCollectorManager::class,
        'arguments' => [
            'totalTypes' => function (ContainerInterface $container) {
                return $container->get(Config::class)->get('total_types');
            }
        ]
    ],
    BasketInterface::class => [
        'class' => Basket::class,
        'arguments' => [
            'currency' => function (ContainerInterface $container) {
                return $container->get(Config::class)->get('currency');
            }
        ]
    ],
    BasketItemInterface::class => [
        'class' => BasketItem::class,
        'arguments' => [
            'currency' => function (ContainerInterface $container) {
                return $container->get(Config::class)->get('currency');
            }
        ]
    ],
    DeliveryConditionRepositoryInterface::class => [
        'class' => ConfigDeliveryConditionRepository::class,
        'arguments' => [
            'currency' => function (ContainerInterface $container) {
                return $container->get(Config::class)->get('currency');
            }
        ]
    ],
    DeliveryCollector::class => [
        'class' => DeliveryCollector::class,
        'arguments' => [
            'currency' => function (ContainerInterface $container) {
                return $container->get(Config::class)->get('currency');
            },
            'totalType' => function (ContainerInterface $container) {
                return $container->get(Config::class)->get(['total_types', 'delivery']);
            }
        ]
    ],
    SubTotalCollector::class => [
        'class' => SubTotalCollector::class,
        'arguments' => [
            'currency' => function (ContainerInterface $container) {
                return $container->get(Config::class)->get('currency');
            },
            'totalType' => function (ContainerInterface $container) {
                return $container->get(Config::class)->get(['total_types', 'subtotal']);
            }
        ]
    ],
];
