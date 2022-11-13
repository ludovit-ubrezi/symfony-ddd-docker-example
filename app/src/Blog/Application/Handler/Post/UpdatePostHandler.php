<?php

namespace Blog\Application\Handler\Post;

use Blog\Application\Command\Post\UpdatePostCommand;
use Blog\Infrastructure\Doctrine\Repository\DoctrinePostRepository;
use Exception;

class UpdatePostHandler
{
    private DoctrinePostRepository $doctrinePostRepository;

    public function __construct(DoctrinePostRepository $doctrinePostRepository)
    {
        $this->doctrinePostRepository = $doctrinePostRepository;
    }

    public function __invoke(UpdatePostCommand $command): void
    {

        $post = $this->doctrinePostRepository->find($command->getId());

        $post->setTitle($command->getTitle());
        $post->setDescription($command->getDescription());
        try {
            $this->doctrinePostRepository->save();
        } catch ( Exception $exception) {
            throw new \RuntimeException("Failed store to repository doctrinePostRepository");
        }
    }

}
