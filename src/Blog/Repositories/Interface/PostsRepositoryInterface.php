<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\Interface;

use GeekBrains\LevelTwo\Blog\Models\Post;
use GeekBrains\LevelTwo\Blog\UUID;

interface PostsRepositoryInterface
{
	public function save(Post $post): void;
	public function get(UUID $author_uuid): Post;
}