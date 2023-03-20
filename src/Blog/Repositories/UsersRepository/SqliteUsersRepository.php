<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\UsersRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserException\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Models\User;
use GeekBrains\LevelTwo\Blog\Person\Name;
use GeekBrains\LevelTwo\Blog\Repositories\Interface\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;
use PDO;
use PDOStatement;

class SqliteUsersRepository implements UsersRepositoryInterface
{
	private PDO $connection;

	public function __construct(PDO $connection)
	{
		$this->connection = $connection;
	}

	public function save(User $user): void
	{
		// Подготавливаем запрос
		$statement = $this->connection
			->prepare(
				'INSERT INTO users (uuid, first_name, last_name, username)
        VALUES (:uuid, :first_name, :last_name, :username)
				ON CONFLICT (uuid) DO UPDATE SET
                    first_name = :first_name,
                    last_name = :last_name'
			);
		// Выполняем запрос с конкретными значениями
		$statement->execute([
			':uuid' => (string)$user->uuid(),
			':first_name' => $user->name()->first(),
			':last_name' => $user->name()->last(),
			':username' => $user->username(),
		]);
	}
	// Также добавим метод для получения
	// пользователя по его UUID
	/**
	 * @throws UserNotFoundException
	 * @throws InvalidArgumentException
	 */
	public function get(UUID $uuid): User
	{
		$statement = $this->connection
			->prepare('SELECT * FROM users WHERE uuid = :uuid');
		$statement->execute([(string)$uuid]);

		return $this->getUser($statement, $uuid);
	}

	/**
	 * @throws UserNotFoundException
	 * @throws InvalidArgumentException
	 */
	public function getUUID(string $uuid): User
	{
		$statement = $this->connection
			->prepare('SELECT * FROM users WHERE uuid = :uuid');
		$statement->execute([
			':uuid' => $uuid,
		]);
		return $this->getUser($statement, $uuid);
	}

	/**
	 * @throws UserNotFoundException
	 * @throws InvalidArgumentException
	 */
	public function getByUsername(string $username): User
	{
		$statement = $this->connection
			->prepare('SELECT * FROM users WHERE username = :username');
		$statement->execute([
			':username' => $username,
		]);
		return $this->getUser($statement, $username);
	}

	/**
	 * @throws UserNotFoundException
	 * @throws InvalidArgumentException
	 */
	private function getUser(PDOStatement $statement, string $errorString): User
	{
		$result = $statement->fetch(PDO::FETCH_ASSOC);
		if ($result === false) {
			throw new UserNotFoundException("Не могу получить пользователя: $errorString\n");
		}
		return new User(
			new UUID($result['uuid']),
			new Name(
				$result['first_name'],
				$result['last_name']
			),
			$result['username']
		);
	}
}