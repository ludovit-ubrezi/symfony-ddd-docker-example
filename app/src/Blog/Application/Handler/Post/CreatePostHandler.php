<?php

namespace Blog\Application\Handler\Post;

use Blog\Application\Command\Post\CreatePostCommand;
use Blog\Domain\Model\Post\Post;
use Blog\Infrastructure\Doctrine\Repository\DoctrinePostRepository;
use Exception;

class CreatePostHandler
{
    private DoctrinePostRepository $doctrinePostRepository;

    public function __construct(DoctrinePostRepository $doctrinePostRepository)
    {
        $this->doctrinePostRepository = $doctrinePostRepository;
    }

    public function __invoke(CreatePostCommand $command): void
    {
        $post = Post::create(
            $command->getTitle(),
            $command->getDescription(),
            $command->getCommentsNumber(),
            $command->getAuthorId()
        );
        try {
            $this->doctrinePostRepository->store($post);
        } catch ( Exception $exception) {
            throw new \RuntimeException("Failed store to repository doctrinePostRepository");
        }
    }

}
