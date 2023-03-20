<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\Interface;

use GeekBrains\LevelTwo\Blog\Models\Comment;
use GeekBrains\LevelTwo\Blog\UUID;

interface CommentsRepositoryInterface
{
	public function save(Comment $comment): void;
	public function get(UUID $comments_uuid): Comment;
}