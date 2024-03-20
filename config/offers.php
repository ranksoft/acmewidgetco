<?php
// config/offers.php

use AcmeWidgetCo\Domain\Strategies\Offers\BuyOneGetOneHalfPrice;

return [
    [
        'class' => BuyOneGetOneHalfPrice::class,
        'priority' => 1,
        'config' => [
            'product_code' => 'R01',
        ],
    ],
];
