<?php

declare(strict_types=1);

namespace shmolf\NotedHydrator\Tests;

use PHPUnit\Framework\TestCase;
use shmolf\NotedHydrator\JsonSchema\Library;
use shmolf\NotedHydrator\Tests\DataObjects\Compatibility;
use Swaggest\JsonSchema\Exception\TypeException;
use Swaggest\JsonSchema\Schema;

class CompatibilitySchemaTest extends TestCase
{
    private Schema $schemaValidator;
    private array $schemas;

    public function setUp(): void
    {
        $this->schemas = Library::getCurrent();
        $this->schemaValidator = Schema::import(
            json_decode(
                file_get_contents($this->schemas['host-compatibility']['file'])
            )
        );
    }

    public function testNoteSchemaHappy(): void
    {
        $isValid = $this->validateSchema(json_encode(Compatibility::getHappy()));

        $this->assertTrue($isValid);
    }

    public function testNoteSchemaSad(): void
    {
        $isValid = $this->validateSchema(json_encode(Compatibility::getSad()));

        $this->assertTrue($isValid);
    }

    public function testNoteSchemaBad(): void
    {
        $this->expectException(TypeException::class);
        $this->validateSchema(json_encode(Compatibility::getBad()));
    }

    private function validateSchema(string $json): bool
    {
        $this->schemaValidator->in(json_decode($json));
        return true;
    }
}
