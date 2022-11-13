<?php

namespace Blog\Application\Command\Comment;

use Blog\Application\Command;
use Blog\Domain\Model\Comment\Comment;

class RemoveCommentCommand implements Command
{
    private Comment $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function getComment(): Comment {
        return $this->comment;
    }
}