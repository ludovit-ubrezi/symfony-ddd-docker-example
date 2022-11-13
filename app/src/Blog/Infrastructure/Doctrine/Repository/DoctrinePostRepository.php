<?php

namespace Blog\Infrastructure\Doctrine\Repository;

use Blog\Domain\Model\Post\Post;
use Blog\Domain\Model\Post\PostRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrinePostRepository extends ServiceEntityRepository implements PostRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function store(Post $post): void
    {
        $this->getEntityManager()->persist($post);
        $this->getEntityManager()->flush();
    }

    public function save(): void
    {
        $this->getEntityManager()->flush();
    }

    public function getAll($limit, $offset): array
    {
        return $this->findBy([], ["date" => "DESC"], $limit, $offset);
    }

    public function delete(Post $post): void {
        $this->getEntityManager()->remove($post);
        $this->getEntityManager()->flush();
    }

}