<?php

declare(strict_types=1);

namespace shmolf\NotedRequestHandler\Tests;

use PHPUnit\Framework\TestCase;
use shmolf\NotedRequestHandler\Exceptions\InvalidSchemaException;
use shmolf\NotedRequestHandler\NoteHydrator;

class NoteHydratorTest extends TestCase
{
    private NoteHydrator $hydrator;

    public function setUp(): void
    {
        $this->hydrator = new NoteHydrator();
    }

    public function tearDown(): void
    {
        unset($_GET[NoteHydrator::CLIENT_VERSION_REQ_KEY]);
    }

    public function testServerVersionResponseHappy(): void
    {
        $_GET[NoteHydrator::CLIENT_VERSION_REQ_KEY] = json_encode([
            'versions' => [NoteHydrator::API_VERSION],
        ]);

        $libraryVersionJson = json_encode([
            'isCompatible' => true,
            'version' => NoteHydrator::API_VERSION,
        ]);

        $this->assertEquals($libraryVersionJson, $this->hydrator->getCompatibilityJsonResponse());
    }

    public function testServerVersionResponseSad(): void
    {
        $_GET[NoteHydrator::CLIENT_VERSION_REQ_KEY] = json_encode([
            'versions' => [(NoteHydrator::API_VERSION + 1)],
        ]);

        $libraryVersionJson = json_encode([
            'isCompatible' => false,
            'version' => NoteHydrator::API_VERSION,
        ]);

        $this->assertEquals($libraryVersionJson, $this->hydrator->getCompatibilityJsonResponse());
    }

    public function testServerVersionResponseBad(): void
    {
        $_GET[NoteHydrator::CLIENT_VERSION_REQ_KEY] = json_encode([
            'versions' => [NoteHydrator::API_VERSION],
        ]);

        $libraryVersionJson = json_encode([
            'isCompatible' => 'true',
            'version' => NoteHydrator::API_VERSION,
        ]);

        $this->assertNotEquals($libraryVersionJson, $this->hydrator->getCompatibilityJsonResponse());
    }

    public function testClientVersionHappy(): void
    {
        $_GET[NoteHydrator::CLIENT_VERSION_REQ_KEY] = json_encode([
            'versions' => [1, 2],
        ]);

        $this->assertTrue($this->hydrator->versionIsSupported());
    }

    public function testClientVersionSad(): void
    {
        $_GET[NoteHydrator::CLIENT_VERSION_REQ_KEY] = json_encode([
            'versions' => [1],
        ]);

        $this->assertTrue($this->hydrator->versionIsSupported());
    }

    public function testClientVersionBadForm(): void
    {
        $this->expectException(InvalidSchemaException::class);

        $_GET[NoteHydrator::CLIENT_VERSION_REQ_KEY] = json_encode([
            'versions' => 1,
        ]);

        $this->hydrator->versionIsSupported();
    }

    public function testClientVersionBadValue(): void
    {
        $this->expectException(InvalidSchemaException::class);

        $_GET[NoteHydrator::CLIENT_VERSION_REQ_KEY] = json_encode([
            'versions' => [0],
        ]);

        $this->hydrator->versionIsSupported();
    }

    public function testClientVersionTerrible(): void
    {
        $this->expectException(InvalidSchemaException::class);

        $_GET[NoteHydrator::CLIENT_VERSION_REQ_KEY] = 'not valid json string at all';

        $this->hydrator->versionIsSupported();
    }
}
