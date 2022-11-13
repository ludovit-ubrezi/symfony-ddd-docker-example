<?php

namespace Blog\Application\Command\Post;

use Blog\Application\Command;
use Blog\Domain\Model\Post\Post;

class RemovePostCommand implements Command
{
    private Post $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function getPost(): Post {
        return $this->post;
    }
}