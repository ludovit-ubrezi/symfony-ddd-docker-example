<?php

namespace Blog\Application\Handler\Post;

use Blog\Application\Command\Post\RemovePostCommand;
use Blog\Infrastructure\Doctrine\Repository\DoctrineCommentRepository;
use Blog\Infrastructure\Doctrine\Repository\DoctrinePostRepository;
use Exception;

class RemovePostHandler
{
    private DoctrineCommentRepository $doctrineCommentRepository;
    private DoctrinePostRepository $doctrinePostRepository;

    public function __construct( DoctrinePostRepository $doctrinePostRepository, DoctrineCommentRepository $doctrineCommentRepository)
    {
        $this->doctrinePostRepository = $doctrinePostRepository;
        $this->doctrineCommentRepository = $doctrineCommentRepository;
    }

    public function __invoke(RemovePostCommand $command): void
    {
        try {
            $this->doctrineCommentRepository->removeAllByPostId($command->getPost()->getId());
            $this->doctrinePostRepository->delete($command->getPost());
        } catch ( Exception $exception) {
            var_dump($exception);
            throw new \RuntimeException("Failed store to repository");
        }
    }
}
