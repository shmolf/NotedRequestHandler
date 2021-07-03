<?php

declare(strict_types=1);

namespace shmolf\NotedHydrator\Entity;

class NoteEntity
{
    public ?string $title;
    public ?string $content;
    /** @var string[] */
    public array $tags = [];
    public bool $inTrashcan;
    public bool $isDeleted;

    public function __construct()
    {
        $this->title = null;
        $this->content = null;
        $this->tags = [];
        $this->inTrashcan = false;
        $this->isDeleted = false;
    }
}
