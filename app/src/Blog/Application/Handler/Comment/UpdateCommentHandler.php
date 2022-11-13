<?php

namespace Blog\Application\Handler\Comment;

use Blog\Application\Command\Comment\UpdateCommentCommand;
use Blog\Infrastructure\Doctrine\Repository\DoctrineCommentRepository;
use Exception;

class UpdateCommentHandler
{
    private DoctrineCommentRepository $doctrineCommentRepository;

    public function __construct(DoctrineCommentRepository $doctrineCommentRepository)
    {
        $this->doctrineCommentRepository = $doctrineCommentRepository;
    }

    public function __invoke(UpdateCommentCommand $command): void
    {
        $comment = $this->doctrineCommentRepository->find($command->getId());
        $comment->setContent($command->getContent());
        try {
            $this->doctrineCommentRepository->save();
        } catch ( Exception $exception) {
            throw new \RuntimeException("Failed store to repository doctrinePostRepository");
        }
    }

}
