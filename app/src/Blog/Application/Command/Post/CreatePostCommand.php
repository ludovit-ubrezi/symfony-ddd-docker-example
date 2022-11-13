<?php

namespace Blog\Application\Command\Post;

use App\Entity\Author;
use Blog\Application\Command;

class CreatePostCommand implements Command
{

    private string $title;
    private string $description;
    private int $commentsNumber;
    private Author $authorId;

    public function __construct(string $title, string $description, Author $author)
    {
        $this->title = $title;
        $this->description = $description;
        $this->commentsNumber = 0;
        $this->authorId = $author;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getCommentsNumber(): string {
        return $this->commentsNumber;
    }

    public function getAuthorId(): Author {
        return $this->authorId;
    }

}