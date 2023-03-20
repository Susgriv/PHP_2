<?php
// Пользователь
namespace GeekBrains\LevelTwo\Blog\Models;

use GeekBrains\LevelTwo\Blog\Person\Name;
use GeekBrains\LevelTwo\Blog\UUID;

class User
{
	private UUID $uuid;
	private Name $name;
	private string $username;

	/**
	 * @param UUID $uuid
	 * @param Name $name
	 * @param string $login
	 */
	public function __construct(UUID $uuid, Name $name, string $login)
	{
		$this->uuid = $uuid;
		$this->name = $name;
		$this->username = $login;
	}

	public function __toString()
	{
		return "Юзер $this->uuid с именем $this->name и логином $this->username" . PHP_EOL;
	}

	public function uuid(): UUID
	{
		return $this->uuid;
	}

	public function name(): Name
	{
		return $this->name;
	}

	public function username(): string
	{
		return $this->username;
	}
}