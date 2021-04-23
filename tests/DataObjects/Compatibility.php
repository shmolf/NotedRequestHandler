<?php

declare(strict_types=1);

namespace shmolf\NotedRequestHandler\Tests\DataObjects;

class Compatibility
{
    public static function getHappy(): array
    {
        return [
            'isCompatible' => true,
            'version' => 1,
        ];
    }

    /**
     * Since there's no optional properties, this'll verify an incompatible API response.
     */
    public static function getSad(): array
    {
        return [
            'isCompatible' => false,
            'version' => 0,
        ];
    }

    public static function getBad(): array
    {
        return [
            'isCompatible' => 'true',
            'version' => '1',
        ];
    }
}
