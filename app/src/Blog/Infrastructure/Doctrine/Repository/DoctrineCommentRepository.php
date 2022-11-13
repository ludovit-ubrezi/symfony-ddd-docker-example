<?php

namespace Blog\Infrastructure\Doctrine\Repository;

use Blog\Domain\Model\Comment\Comment;
use Blog\Domain\Model\Comment\CommentRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineCommentRepository extends ServiceEntityRepository implements CommentRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function store(Comment $comment): void
    {
        $this->getEntityManager()->persist($comment);
        $this->getEntityManager()->flush();
    }

    public function save(): void
    {
        $this->getEntityManager()->flush();
    }

    public function removeAllByPostId($postId): void {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->delete(Comment::class, 'c')
            ->where('c.postId = :postId')
            ->setParameter('postId', $postId);

        $query = $qb->getQuery();
        $query->execute();
    }

    public function findByPostId($postId, $limit, $offset): array
    {
        return $this->findBy(["postId" => $postId], ["createdAt" => "DESC"], $limit, $offset);
    }

    public function delete(Comment $comment): void {
        $this->getEntityManager()->remove($comment);
        $this->getEntityManager()->flush();
    }

}