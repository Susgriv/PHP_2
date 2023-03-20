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

	/**
	 * @return UUID
	 */
	public function getComment_uuid(): UUID
	{
		return $this->uuid;
	}

	/**
	 * @return Post
	 */
	public function getPostUuid(): Post
	{
		return $this->postUuid;
	}

	/**
	 * @return User
	 */
	public function getAuthorUuid(): User
	{
		return $this->post_author_uuid;
	}

	/**
	 * @return string
	 */
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