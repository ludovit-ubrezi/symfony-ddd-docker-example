<?php

namespace Blog\Application\Handler\Comment;

use Blog\Application\Command\Comment\CreateCommentCommand;
use Blog\Domain\Model\Comment\Comment;
use Blog\Infrastructure\Doctrine\Repository\DoctrineCommentRepository;
use Blog\Infrastructure\Doctrine\Repository\DoctrinePostRepository;
use Exception;

class CreateCommentHandler
{
    private DoctrineCommentRepository $doctrineCommentRepository;
    private DoctrinePostRepository $doctrinePostRepository;

    public function __construct(DoctrineCommentRepository $doctrineCommentRepository, DoctrinePostRepository $doctrinePostRepository)
    {
        $this->doctrineCommentRepository = $doctrineCommentRepository;
        $this->doctrinePostRepository = $doctrinePostRepository;
    }

    public function __invoke(CreateCommentCommand $command): void
    {

        $comment = Comment::create(
            $command->getPostId(),
            $command->getAuthor(),
            $command->getContent()
        );
        try {
            $this->doctrineCommentRepository->store($comment);
            $this->updateCommentPost($comment->getPostId());
        } catch ( Exception $exception) {
            var_dump($exception);
            throw new \RuntimeException("Failed store to repository doctrineCommentRepository");
        }
    }

    private function updateCommentPost(int $postId): void {
        $post = $this->doctrinePostRepository->find($postId);
        $post->increaseCommentsNumber();
        $post->date = new \DateTime();
        $this->doctrinePostRepository->store($post);
    }
}
