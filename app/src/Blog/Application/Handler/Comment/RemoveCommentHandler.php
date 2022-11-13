<?php

namespace Blog\Application\Handler\Comment;

use Blog\Application\Command\Comment\RemoveCommentCommand;
use Blog\Infrastructure\Doctrine\Repository\DoctrineCommentRepository;
use Blog\Infrastructure\Doctrine\Repository\DoctrinePostRepository;
use Exception;

class RemoveCommentHandler
{
    private DoctrineCommentRepository $doctrineCommentRepository;
    private DoctrinePostRepository $doctrinePostRepository;

    public function __construct(DoctrineCommentRepository $doctrineCommentRepository, DoctrinePostRepository $doctrinePostRepository)
    {
        $this->doctrineCommentRepository = $doctrineCommentRepository;
        $this->doctrinePostRepository = $doctrinePostRepository;
    }

    public function __invoke(RemoveCommentCommand $command): void
    {
        try {
            $this->doctrineCommentRepository->delete($command->getComment());
            $this->updatePostCommentsCount($command->getComment()->getPostId());
        } catch ( Exception $exception) {
            var_dump($exception);
            throw new \RuntimeException("Failed store to repository");
        }
    }

    private function updatePostCommentsCount(int $postId): void {
        $post = $this->doctrinePostRepository->find($postId);
        $post->decreaseCommentsNumber();
        $this->doctrinePostRepository->store($post);
    }
}
