<?php

namespace Blog\Domain\Model\Post;

use App\Entity\Author;
use Blog\Infrastructure\Doctrine\Repository\DoctrinePostRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity(repositoryClass: DoctrinePostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\Column(length: 255)]
    public ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    public ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    public ?\DateTimeInterface $date = null;

    #[ORM\Column]
    public ?int $commentsNumber = null;

    #[ManyToOne(targetEntity: Author::class)]
    #[JoinColumn(name: 'author_id', referencedColumnName: 'id')]
    public ?Author $author = null;

    private function __construct(string $title, string $description, DateTimeInterface $date, int $commentsNumber, Author $author)
    {
        $this->title = $title;
        $this->description = $description;
        $this->date = $date;
        $this->commentsNumber = $commentsNumber;
        $this->author = $author;
    }

    public static function create(
        string $title, string $description, int $commentsNumber, Author $author
    ): self
    {
        return new self($title, $description, new \DateTime(), $commentsNumber, $author);
    }

    public function getId(): int {
        return $this->id;
    }

    public function increaseCommentsNumber(): void {
        $this->commentsNumber++;
    }

    public function decreaseCommentsNumber(): void {
        $this->commentsNumber--;
        if ($this->commentsNumber < 0) {
            $this->commentsNumber = 0;
        }
    }

    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }
}
