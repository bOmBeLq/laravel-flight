<?php

namespace App\Models\Enum;

enum ActivityType: string
{
    case DAY_OFF = 'DAY_OFF';
    case STAND_BY = 'STAND_BY';
    case FLIGHT = 'FLIGHT';
    case UNKNOWN = 'UNKNOWN';


    /**
     * @return string[]
     */
    public static function stringCases(): array
    {
        return array_map(function (ActivityType $activityType) {
            return $activityType->value;
        }, self::cases());
    }

}
