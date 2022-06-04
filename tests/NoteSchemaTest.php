<?php

declare(strict_types=1);

namespace shmolf\NotedHydrator\Tests;

use PHPUnit\Framework\TestCase;
use shmolf\NotedHydrator\JsonSchema\BaseLibrary;
use shmolf\NotedHydrator\JsonSchema\v2\Library;
use shmolf\NotedHydrator\Tests\DataObjects\Note;
use Swaggest\JsonSchema\Exception\TypeException;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\SchemaContract;

class NoteSchemaTest extends TestCase
{
    private SchemaContract $schemaValidator;
    private BaseLibrary $library;

    public function setUp(): void
    {
        $this->library = new Library();
        $this->schemaValidator = Schema::import(
            json_decode(
                file_get_contents($this->library->noteSchemaFilePath()),
            )
        );
    }

    public function testNoteSchemaHappy(): void
    {
        $isValid = $this->validateSchema(json_encode(Note::getHappy()));

        $this->assertTrue($isValid);
    }

    public function testNoteSchemaSadMissProps(): void
    {
        $isValid = $this->validateSchema(json_encode(Note::getSadMissingProperties()));

        $this->assertTrue($isValid);
    }

    public function testNoteSchemaSadEmptyProps(): void
    {
        $isValid = $this->validateSchema(json_encode(Note::getSadEmptyProperties()));

        $this->assertTrue($isValid);
    }

    public function testNoteSchemaBad(): void
    {
        $this->expectException(TypeException::class);
        $this->validateSchema(json_encode(Note::getBad()));
    }

    private function validateSchema(string $json): bool
    {
        $this->schemaValidator->in(json_decode($json));
        return true;
    }
}
