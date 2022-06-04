<?php

declare(strict_types=1);

namespace shmolf\NotedHydrator\Tests;

use PHPUnit\Framework\TestCase;
use shmolf\NotedHydrator\JsonSchema\BaseLibrary;
use shmolf\NotedHydrator\JsonSchema\v2\Library;
use shmolf\NotedHydrator\Tests\DataObjects\Compatibility;
use Swaggest\JsonSchema\Exception\TypeException;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\SchemaContract;

class CompatibilitySchemaTest extends TestCase
{
    private SchemaContract $schemaValidator;
    private BaseLibrary $library;

    public function setUp(): void
    {
        $this->library = new Library();
        $this->schemaValidator = Schema::import(
            json_decode(
                file_get_contents($this->library->hostCompatibilitySchemaFilePath()),
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
