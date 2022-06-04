<?php

declare(strict_types=1);

namespace shmolf\NotedHydrator\JsonSchema\v1;
use shmolf\NotedHydrator\JsonSchema\BaseLibrary;

class Library extends BaseLibrary
{
    protected string $version = 'v1';
    public int $apiVersion = 1;
}
