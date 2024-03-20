<?php
declare(strict_types=1);

namespace AcmeWidgetCo\Domain\Enums;

enum DeliveryConditionType: string
{
    case LessThan = 'less_than';
    case LessThanOrEqual = 'less_than_or_equal';
    case Equal = 'equal';
    case GreaterThan = 'greater_than';
    case GreaterThanOrEqual = 'greater_than_or_equal';

    public function getPriority(): int
    {
        return match ($this) {
            self::LessThan => 1,
            self::LessThanOrEqual => 2,
            self::Equal => 3,
            self::GreaterThan => 4,
            self::GreaterThanOrEqual => 5,
        };
    }
}

