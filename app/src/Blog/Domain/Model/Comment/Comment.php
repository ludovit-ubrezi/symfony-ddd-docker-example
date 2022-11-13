<?php

namespace Blog\Domain\Model\Comment;

use App\Entity\Author;
use Blog\Infrastructure\Doctrine\Repository\DoctrineCommentRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity(repositoryClass: DoctrineCommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\Column]
    public ?int $postId = null;

    #[ManyToOne(targetEntity: Author::class)]
    #[JoinColumn(name: 'author_id', referencedColumnName: 'id')]
    public ?Author $author = null;

    #[ORM\Column(type: Types::TEXT)]
    public ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    public ?\DateTimeInterface $createdAt = null;

    private function __construct(int $postId, Author $author, string $content, DateTimeInterface $createdAt)
    {
        $this->postId = $postId;
        $this->author = $author;
        $this->content = $content;
        $this->createdAt = $createdAt;
    }

    public static function create(int $postId,  Author $author, string $content): self
    {
        return new self($postId, $author, $content, new \DateTime());
    }

    public function getId(): int {
        return $this->id;
    }

    public function getPostId(): int {
        return $this->postId;
    }

    public function setContent(string $content): void {
        $this->content = $content;
    }
}
