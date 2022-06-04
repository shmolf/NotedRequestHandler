<?php

declare(strict_types=1);

namespace shmolf\NotedHydrator\JsonSchema;

abstract class BaseLibrary
{
    private const PROTO_HOST = 'https://note-d.app';
    protected const VERSION = 0;

    protected string $protoHost;
    protected string $version;
    public int $apiVersion;

    /**
     * @param null|string $schemaHost - If not specified, will attempt to pull from the ENV variable `NOTED_SCHEMA_HOST`
     *                              If that is not set, will default to `https://note-d.app`
     */
    public function __construct(?string $schemaHost = null)
    {
        $this->protoHost = $schemaHost ?? getenv('NOTED_SCHEMA_HOST') ?: self::PROTO_HOST;
        $this->version = 'v' . $this::VERSION;
        $this->apiVersion = $this::VERSION;
    }

    public function noteSchemaFilePath(): string
    {
        return "{$this->localDirPath()}/note.json";
    }

    /**
     * @return string ex: `https://note-d.app/schemas/v1/note.json`
     */
    public function noteSchemaPath(): string
    {
        return "{$this->uriPath()}/note.json";
    }

    public function clientCompatibilitySchemaFilePath(): string
    {
        return "{$this->localDirPath()}/client-compatibility.json";
    }

    public function hostCompatibilitySchemaFilePath(): string
    {
        return "{$this->localDirPath()}/host-compatibility.json";
    }

    /**
     * @return string
     */
    protected function uriPath()
    {
        return "{$this->protoHost}/schemas/{$this->version}";
    }

    protected function localDirPath(): string
    {
        return __DIR__ . "/{$this->version}";
    }
}
