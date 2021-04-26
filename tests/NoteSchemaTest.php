<?php

declare(strict_types=1);

namespace shmolf\NotedRequestHandler\Tests;

use PHPUnit\Framework\TestCase;
use shmolf\NotedRequestHandler\JsonSchema\Library;
use shmolf\NotedRequestHandler\Tests\DataObjects\Note;
use Swaggest\JsonSchema\Exception\StringException;
use Swaggest\JsonSchema\Schema;

class NoteSchemaTest extends TestCase
{
    private Schema $schemaValidator;
    private array $schemas;

    public function setUp(): void
    {
        $this->schemas = Library::getCurrent();
        $this->schemaValidator = Schema::import(
            json_decode(
                file_get_contents($this->schemas['note']['file'])
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
        $this->expectException(StringException::class);
        $this->validateSchema(json_encode(Note::getBad()));
    }

    private function validateSchema(string $json): bool
    {
        $this->schemaValidator->in(json_decode($json));
        return true;
    }
}
