<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\UsersRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\UserException\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\Interface\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Person\Name;
use GeekBrains\LevelTwo\Blog\Models\User;
use GeekBrains\LevelTwo\Blog\UUID;

// Dummy - чучело, манекен
class DummyUsersRepository implements UsersRepositoryInterface
{
	public function save(User $user): void
	{
// Ничего не делаем
	}

	/**
	 * @throws UserNotFoundException
	 */
	public function get(UUID $uuid): User
	{
// И здесь ничего не делаем
		throw new UserNotFoundException("Not found");
	}

	public function getByUsername(string $username): User
	{
// Нас интересует реализация только этого метода
// Для нашего теста не важно, что это будет за пользователь,
// поэтому возвращаем совершенно произвольного
		return new User(UUID::random(), new Name("first", "last"), "user123");
	}
}