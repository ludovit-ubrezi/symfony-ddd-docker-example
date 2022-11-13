<?php

namespace Blog\Application\Query;

use Blog\Domain\Model\Comment\Comment;
use Blog\Domain\Model\Comment\CommentRepository;
use Blog\Infrastructure\Doctrine\Repository\DoctrineCommentRepository;

class CommentQuery
{
    private CommentRepository $commentRepository;

    public function __construct(DoctrineCommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function findByPostId(int $postId, int $limit, int $offset): array {
        return $this->commentRepository->findByPostId($postId, $limit, $offset);
    }
}