<?php
// config/delivery_conditions.php

use AcmeWidgetCo\Domain\Enums\DeliveryConditionType;

return [
    [
        'condition' => DeliveryConditionType::LessThan->value,
        'value' => 50,
        'cost' => 4.95,
    ],
    [
        'condition' => DeliveryConditionType::LessThan->value,
        'value' => 90,
        'cost' => 2.95,
    ],
    [
        'condition' => DeliveryConditionType::GreaterThan->value,
        'value' => 90,
        'cost' => 0,
    ],
];
