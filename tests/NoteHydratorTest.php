<?php

declare(strict_types=1);

namespace shmolf\NotedHydrator\Tests;

use PHPUnit\Framework\TestCase;
use shmolf\NotedHydrator\Entity\NoteEntity;
use shmolf\NotedHydrator\JsonSchema\Library;
use shmolf\NotedHydrator\NoteHydrator;
use Swaggest\JsonSchema\Exception\StringException;

class NoteHydratorTest extends TestCase
{
    private NoteHydrator $hydrator;

    public function setUp(): void
    {
        $this->hydrator = new NoteHydrator();
    }

    public function tearDown(): void
    {
        unset($_GET[NoteHydrator::REQ_API_VERSION]);
    }

    public function testServerVersionResponseHappy(): void
    {
        $_GET[NoteHydrator::REQ_API_VERSION] = json_encode([
            'versions' => [Library::CUR_VERSION],
        ]);

        $libraryVersionJson = json_encode([
            'isCompatible' => true,
            'version' => Library::CUR_VERSION,
        ]);

        $this->assertEquals($libraryVersionJson, $this->hydrator->getCompatibilityJsonResponse());
    }

    public function testServerVersionResponseSad(): void
    {
        $_GET[NoteHydrator::REQ_API_VERSION] = json_encode([
            'versions' => [(Library::CUR_VERSION + 1)],
        ]);

        $libraryVersionJson = json_encode([
            'isCompatible' => false,
            'version' => Library::CUR_VERSION,
        ]);

        $this->assertEquals($libraryVersionJson, $this->hydrator->getCompatibilityJsonResponse());
    }

    public function testServerVersionResponseBad(): void
    {
        $_GET[NoteHydrator::REQ_API_VERSION] = json_encode([
            'versions' => [Library::CUR_VERSION],
        ]);

        $libraryVersionJson = json_encode([
            'isCompatible' => 'true',
            'version' => Library::CUR_VERSION,
        ]);

        $this->assertNotEquals($libraryVersionJson, $this->hydrator->getCompatibilityJsonResponse());
    }

    public function testClientVersionHappy(): void
    {
        $_GET[NoteHydrator::REQ_API_VERSION] = json_encode([
            'versions' => [1, 2],
        ]);

        $this->assertTrue($this->hydrator->versionIsSupported());
    }

    public function testClientVersionSad(): void
    {
        $_GET[NoteHydrator::REQ_API_VERSION] = json_encode([
            'versions' => [1],
        ]);

        $this->assertTrue($this->hydrator->versionIsSupported());
    }

    public function testClientVersionBadForm(): void
    {

        $_GET[NoteHydrator::REQ_API_VERSION] = json_encode([
            'versions' => 1,
        ]);

        $this->assertFalse($this->hydrator->versionIsSupported());
    }

    public function testClientVersionBadValue(): void
    {
        $_GET[NoteHydrator::REQ_API_VERSION] = json_encode([
            'versions' => [0],
        ]);

        $this->assertFalse($this->hydrator->versionIsSupported());
    }

    public function testClientVersionTerrible(): void
    {
        $_GET[NoteHydrator::REQ_API_VERSION] = 'not valid json string at all';

        $this->assertFalse($this->hydrator->versionIsSupported());
    }

    public function testNoteHydrationHappy(): void
    {
        $goodUuid = 'e0f9d9cf-02b3-4b8a-b5c0-ea094c07b0b9';

        $noteArray = [
            'noteUuid' => $goodUuid,
            'clientUuid' => $goodUuid,
            'title' => 'test title',
            'content' => 'test content',
            'tags' => ['tag 1', 'tag 2'],
        ];

        $testNote = new NoteEntity();
        $testNote->setNoteUuid($goodUuid);
        $testNote->setClientUuid($goodUuid);
        $testNote->title = $noteArray['title'];
        $testNote->content = $noteArray['content'];
        $testNote->tags = $noteArray['tags'];

        $noteJson = json_encode($noteArray);
        $generatedNote = $this->hydrator->getHydratedNote($noteJson);

        $this->assertEquals($testNote, $generatedNote);
    }

    public function testNoteHydrationSad(): void
    {
        $goodUuid = 'e0f9d9cf-02b3-4b8a-b5c0-ea094c07b0b9';

        $noteArray = [
            'clientUuid' => $goodUuid,
            'title' => 'test title',
            'content' => 'test content',
        ];

        $testNote = new NoteEntity();
        $testNote->setClientUuid($goodUuid);
        $testNote->title = $noteArray['title'];
        $testNote->content = $noteArray['content'];

        $noteJson = json_encode($noteArray);
        $generatedNote = $this->hydrator->getHydratedNote($noteJson);

        $this->assertEquals($testNote, $generatedNote);
    }

    public function testNoteHydrationBad(): void
    {
        $badUuid = 'this is not a valid uuid string';

        // Missing a Client UUID
        $noteArray = [
            'clientUuid' => $badUuid,
            'title' => 'test title',
            'content' => 'test content',
            'tags' => ['tag 1', 'tag 2'],
        ];

        $testNote = new NoteEntity();
        $testNote->setClientUuid($badUuid);
        $testNote->title = $noteArray['title'];
        $testNote->content = $noteArray['content'];
        $testNote->tags = $noteArray['tags'];

        $noteJson = json_encode($noteArray);
        $this->assertNull($this->hydrator->getHydratedNote($noteJson));
    }
}
