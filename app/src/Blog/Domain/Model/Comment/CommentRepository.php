<?php

namespace Blog\Domain\Model\Comment;


interface CommentRepository
{
    public function store(Comment $post): void;

    public function save(): void;

}