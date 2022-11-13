<?php
declare(strict_types=1);

namespace Blog\Infrastructure\Doctrine\Id;

trait AutoIncrementId
{
    /**
     * @var int|null
     */
    private $id;

    private function __construct(int $id = null)
    {
        $this->id = $id;
    }

    public function __toString(): string
    {
        return (string)$this->id;
    }

    public function toInteger(): int
    {
        return $this->id;
    }

    public function jsonSerialize(): int
    {
        return $this->id;
    }

    public function isInitialized(): bool
    {
        return null !== $this->id;
    }
}
