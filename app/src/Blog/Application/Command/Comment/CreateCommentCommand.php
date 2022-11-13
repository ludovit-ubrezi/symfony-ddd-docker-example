<?php

namespace Blog\Application\Command\Comment;

use App\Entity\Author;
use Blog\Application\Command;

class CreateCommentCommand implements Command
{
    private int $postId;
    private Author $author;
    private string $content;

    public function __construct(int $postId,  Author $author, string $content)
    {
        $this->postId = $postId;
        $this->author = $author;
        $this->content = $content;
    }

    public function getPostId(): int {
        return $this->postId;
    }

    public function getAuthor(): Author {
        return $this->author;
    }

    public function getContent(): string {
        return $this->content;
    }
}