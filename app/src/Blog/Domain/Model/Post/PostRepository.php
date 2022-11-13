<?php

namespace Blog\Domain\Model\Post;

interface PostRepository
{
    public function store(Post $post): void;

    public function save(): void;
}