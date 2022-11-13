<?php

namespace Blog\Application\Query;

use Blog\Domain\Model\Post\PostRepository;
use Blog\Infrastructure\Doctrine\Repository\DoctrinePostRepository;

class PostQuery
{
    private PostRepository $postRepository;

    public function __construct(DoctrinePostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getAll(int $limit, int $offset): array {
        return $this->postRepository->getAll($limit, $offset);
    }

}