<?php

declare(strict_types=1);

namespace shmolf\NotedHydrator\JsonSchema;

class Library
{
    private const PROTO_HOST = 'https://note-d.app';
    public const CUR_VERSION = 2;
    private const CUR_VERSION_PATH = '/v2';
    /** @var string `https://note-d.app/schemas/v1/note.json` */
    public const CUR_URI_PATH = self::PROTO_HOST . '/schemas' . self::CUR_VERSION_PATH;
    /** @var string */
    public const CUR_FILE_PATH = __DIR__ . self::CUR_VERSION_PATH;

    /**
     * @return array<string, array<string, string>>
     */
    public static function getCurrent(): array
    {
        return [
            'note' => [
                'uri' => self::CUR_URI_PATH . '/note.json',
                'file' => self::CUR_FILE_PATH . '/note.json',
            ],
            'host-compatibility' => [
                'uri' => self::CUR_URI_PATH . '/host-compatibility.json',
                'file' => self::CUR_FILE_PATH . '/host-compatibility.json',
            ],
            'client-compatibility' => [
                'uri' => self::CUR_URI_PATH . '/client-compatibility.json',
                'file' => self::CUR_FILE_PATH . '/client-compatibility.json',
            ],
        ];
    }

    public static function getAlternatives(): array
    {
        $v1 = '/v1';
        $v1Uri = self::PROTO_HOST . "/schemas{$v1}";
        $v1Path = __DIR__ . $v1;

        return [
            'note' => [
                'uri' => "{$v1Uri}/note.json",
                'file' => "{$v1Path}/note.json",
            ],
            'host-compatibility' => [
                'uri' => "{$v1Uri}/host-compatibility.json",
                'file' =>  "{$v1Path}/host-compatibility.json",
            ],
            'client-compatibility' => [
                'uri' => "{$v1Uri}/client-compatibility.json",
                'file' =>  "{$v1Path}/client-compatibility.json",
            ],
        ];
    }
}
