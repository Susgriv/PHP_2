<?php

namespace GeekBrains\LevelTwo\Blog\Models;

use GeekBrains\LevelTwo\Blog\UUID;

class Post
{
	public function __construct(
		private UUID   $uuid,
		private User   $author,
		private string $title,
		private string $text
	)
	{
	}

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->uuid . ' ' . 'Заголовок:' . ' ' . $this->title . PHP_EOL .
			$this->author . 'пишет: ' . $this->text . PHP_EOL;
	}

	/**
	 * @return UUID
	 */
	public function getPost_uuid(): UUID
	{
		return $this->uuid;
	}

	/**
	 * @return User
	 */
	public function getAuthor(): User
	{
		return $this->author;
	}

	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function getText(): string
	{
		return $this->text;
	}
}