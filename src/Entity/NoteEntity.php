<?php

declare(strict_types=1);

namespace shmolf\NotedHydrator\Entity;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class NoteEntity
{
    public ?UuidInterface $noteUuid;
    public ?UuidInterface $clientUuid;
    public ?string $title;
    public ?string $content;
    /** @var string[] */
    public array $tags = [];
    public bool $inTrashcan;
    public bool $isDeleted;

    public function __construct()
    {
        $this->noteUuid = null;
        $this->clientUuid = null;
        $this->title = null;
        $this->content = null;
        $this->tags = [];
        $this->inTrashcan = false;
        $this->isDeleted = false;
    }

    public function setNoteUuid(string $uuid): void
    {
        $this->noteUuid = Uuid::isValid($uuid) ? Uuid::fromString($uuid) : null;
    }

    public function getNoteUuidAsString(): ?string
    {
        return $this->noteUuid instanceof UuidInterface ? $this->noteUuid->toString() : null;
    }

    public function setClientUuid(string $uuid): void
    {
        $this->clientUuid = Uuid::isValid($uuid) ? Uuid::fromString($uuid) : null;
    }

    public function getClientUuidAsString(): ?string
    {
        return $this->clientUuid instanceof UuidInterface ? $this->clientUuid->toString() : null;
    }
}
