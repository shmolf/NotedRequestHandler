<?php

declare(strict_types=1);

namespace shmolf\NotedHydrator\Tests\DataObjects;

class ClientCompatibility
{
    public static function getHappy(): array
    {
        return [
            'versions' => [1, 2, 3, 4],
        ];
    }

    public static function getSad(): array
    {
        return [
            'versions' => [1],
        ];
    }

    public static function getBad(): array
    {
        return [
            'versions' => [0],
        ];
    }
}
