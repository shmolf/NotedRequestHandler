<?php

declare(strict_types=1);

namespace shmolf\NotedHydrator\Tests\DataObjects;

class Note
{
    public static function getHappy(): array
    {
        return [
            'title' => 'test title',
            'content' => 'content',
            'tags' => [
                'test tag1',
                'test tag2',
            ],
        ];
    }

    public static function getSadMissingProperties(): array
    {
        return [
            'title' => 'test title',
            'content' => 'content',
        ];
    }

    public static function getSadEmptyProperties(): array
    {
        return [
            'title' => '',
            'content' => '',
            'tags' => [],
        ];
    }

    public static function getBad(): array
    {
        return [
            'title' => 13,
            'content' => null,
            'tags' => [
                13,
                null,
            ],
        ];
    }
}
