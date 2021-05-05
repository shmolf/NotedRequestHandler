<?php

declare(strict_types=1);

namespace shmolf\NotedHydrator\Tests\DataObjects;

class Note
{
    public static function getHappy(): array
    {
        return [
            'noteUuid' => '0123abcd-45ef-46ab-a890-123456abcedf',
            'clientUuid' => '0123abcd-45ef-46ab-a890-123456abcedf',
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
            'clientUuid' => '0123abcd-45ef-46ab-a890-123456abcedf',
            'title' => 'test title',
            'content' => 'content',
        ];
    }

    public static function getSadEmptyProperties(): array
    {
        return [
            'noteUuid' => null,
            'clientUuid' => null,
            'title' => '',
            'content' => '',
            'tags' => [],
        ];
    }

    public static function getBad(): array
    {
        return [
            'noteUuid' => 'invalid string',
            'clientUuid' => 'invalid string',
            'title' => 13,
            'content' => null,
            'tags' => [
                13,
                null,
            ],
        ];
    }
}
