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
	
	public function __toString(): string
	{
		return $this->uuid . ' ' . 'Заголовок:' . ' ' . $this->title . PHP_EOL .
			$this->author . 'пишет: ' . $this->text . PHP_EOL;
	}
	
	public function getPost_uuid(): UUID
	{
		return $this->uuid;
	}
	
	public function getAuthor(): User
	{
		return $this->author;
	}
	
	public function getTitle(): string
	{
		return $this->title;
	}
	
	public function getText(): string
	{
		return $this->text;
	}
}