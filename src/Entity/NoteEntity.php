<?php
namespace shmolf\NotedRequestHandler\Entity;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class NoteEntity
{
    public ?UuidInterface $noteUuid = null;
    public ?UuidInterface $clientUuid = null;
    public string $title;
    public string $content;
    /** @var string[] */
    public array $tags = [];

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
