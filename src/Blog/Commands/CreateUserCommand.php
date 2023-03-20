<?php

namespace GeekBrains\LevelTwo\Blog\Commands;

use GeekBrains\LevelTwo\Blog\Exceptions\ArgumentsException;
use GeekBrains\LevelTwo\Blog\Exceptions\CommandException;
use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserException\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Models\User;
use GeekBrains\LevelTwo\Blog\Person\Name;
use GeekBrains\LevelTwo\Blog\Repositories\Interface\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;

class CreateUserCommand
{
	// Команда зависит от контракта репозитория пользователей,
	// а не от конкретной реализации
	public function __construct(
		private UsersRepositoryInterface $usersRepository
	)
	{
	}

	/**
	 * @throws CommandException
	 * @throws InvalidArgumentException | ArgumentsException
	 */
	public function handle(Arguments $arguments): void
	{
		$username = $arguments->get('username');
		// Проверяем, существует ли пользователь в репозитории
		if ($this->userExists($username)) {
		// Бросаем исключение, если пользователь уже существует
			throw new CommandException("User already exists: $username");
		}
		// Сохраняем пользователя в репозиторий
		$this->usersRepository->save(
			new User(
				UUID::random(),
				new Name(
					$arguments->get('first_name'),
					$arguments->get('last_name')
				),
				$username,
			));
	}

	private function userExists(string $username): bool
	{
		try {
			// Пытаемся получить пользователя из репозитория
			$this->usersRepository->getByUsername($username);
		} catch (UserNotFoundException) {
			return false;
		}
		return true;
	}
}