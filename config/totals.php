<?php
// config/totals.php

use AcmeWidgetCo\Domain\TotalCollectors\SubTotalCollector;
use AcmeWidgetCo\Domain\TotalCollectors\DiscountCollector;
use AcmeWidgetCo\Domain\TotalCollectors\DeliveryCollector;
use AcmeWidgetCo\Infrastructure\Config\Config;

$config = new Config();
return [
    $config->get(['total_types', 'subtotal']) => [
        'collectors' => [
            SubTotalCollector::class,
        ],
        'priority' => 1,
    ],
    $config->get(['total_types', 'delivery']) => [
        'collectors' => [
            DeliveryCollector::class,
        ],
        'priority' => 3,
    ],
];