<?php

namespace GeekBrains\LevelTwo\Blog\Models;

use GeekBrains\LevelTwo\Blog\UUID;

class Comment
{
	public function __construct(
		private UUID   $uuid,
		private Post   $postUuid,
		private User   $post_author_uuid,
		private string $textComment,
	)
	{
	}

	public function getComment_uuid(): UUID
	{
		return $this->uuid;
	}

	public function getPostUuid(): Post
	{
		return $this->postUuid;
	}

	public function getAuthorUuid(): User
	{
		return $this->post_author_uuid;
	}

	public function getTextComment(): string
	{
		return $this->textComment;
	}

	public function __toString()
	{
		return 'ID:' . $this->uuid . ' ' . 'Пользователь:' . ' ' . $this->post_author_uuid .
			' ' . 'Написал комментарий:' . ' ' . $this->textComment . ' ' . 'к посту' .
			'id' . $this->postUuid;
	}
}