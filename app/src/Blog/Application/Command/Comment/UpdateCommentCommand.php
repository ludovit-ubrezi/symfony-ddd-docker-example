<?php

namespace Blog\Application\Command\Comment;

use Blog\Application\Command;

class UpdateCommentCommand implements Command
{
    private int $id;
    private string $content;

    public function __construct(int $id, string $content)
    {
        $this->id = $id;
        $this->content = $content;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getContent(): string {
        return $this->content;
    }
}