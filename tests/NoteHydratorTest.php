<?php

declare(strict_types=1);

namespace shmolf\NotedHydrator\Tests;

use PHPUnit\Framework\TestCase;
use shmolf\NotedHydrator\Entity\NoteEntity;
use shmolf\NotedHydrator\JsonSchema\BaseLibrary;
use shmolf\NotedHydrator\JsonSchema\v2\Library;
use shmolf\NotedHydrator\NoteHydrator;

class NoteHydratorTest extends TestCase
{
    private NoteHydrator $hydrator;
    private BaseLibrary $library;

    public function setUp(): void
    {
        $this->library = new Library();
        $this->hydrator = new NoteHydrator($this->library);
    }

    public function tearDown(): void
    {
        unset($_GET[NoteHydrator::REQ_API_VERSION]);
    }

    public function testServerVersionResponseHappy(): void
    {
        $_GET[NoteHydrator::REQ_API_VERSION] = json_encode([
            'versions' => [$this->library->apiVersion],
        ]);

        $libraryVersionJson = json_encode([
            'isCompatible' => true,
            'version' => $this->library->apiVersion,
        ]);

        $this->assertEquals($libraryVersionJson, $this->hydrator->getCompatibilityJsonResponse());
    }

    public function testServerVersionResponseSad(): void
    {
        $_GET[NoteHydrator::REQ_API_VERSION] = json_encode([
            'versions' => [($this->library->apiVersion + 1)],
        ]);

        $libraryVersionJson = json_encode([
            'isCompatible' => false,
            'version' => $this->library->apiVersion,
        ]);

        $this->assertEquals($libraryVersionJson, $this->hydrator->getCompatibilityJsonResponse());
    }

    public function testServerVersionResponseBad(): void
    {
        $_GET[NoteHydrator::REQ_API_VERSION] = json_encode([
            'versions' => [$this->library->apiVersion],
        ]);

        $libraryVersionJson = json_encode([
            'isCompatible' => 'true',
            'version' => $this->library->apiVersion,
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
            'versions' => [$this->library->apiVersion],
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
        $noteArray = [
            'title' => 'test title',
            'content' => 'test content',
            'tags' => ['tag 1', 'tag 2'],
        ];

        $testNote = new NoteEntity();
        $testNote->title = $noteArray['title'];
        $testNote->content = $noteArray['content'];
        $testNote->tags = $noteArray['tags'];

        $noteJson = json_encode($noteArray);
        $generatedNote = $this->hydrator->getHydratedNote($noteJson);

        $this->assertEquals($testNote, $generatedNote);
    }

    public function testNoteHydrationSad(): void
    {
        $noteArray = [
            'title' => 'test title',
            'content' => 'test content',
        ];

        $testNote = new NoteEntity();
        $testNote->title = $noteArray['title'];
        $testNote->content = $noteArray['content'];

        $noteJson = json_encode($noteArray);
        $generatedNote = $this->hydrator->getHydratedNote($noteJson);

        $this->assertEquals($testNote, $generatedNote);
    }
}
