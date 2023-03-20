<?php

namespace GeekBrains\LevelTwo\Blog\UnitTest\Commands\Users;

use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserException\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Models\User;
use GeekBrains\LevelTwo\Blog\Person\Name;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\UUID;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class SqliteUsersRepositoryTest extends TestCase
{
	// Тест, проверяющий, что SQLite-репозиторий бросает исключение,
	// когда запрашиваемый пользователь не найден
	/**
	 * @throws InvalidArgumentException
	 */
	public function testItThrowsAnExceptionWhenUserNotFound(): void
	{
		// Сначала нам нужно подготовить все стабы
		// 2. Создаём стаб подключения
		$connectionMock = $this->createStub(PDO::class);
		// 4. Создаем Стаб запроса
		$statementStub = $this->createStub(PDOStatement::class);
		// 5. Стаб запроса будет возвращать false при вызове метода fetch
		$statementStub->method('fetch')->willReturn(false);
		// 3. Стаб подключения будет возвращать другой стаб - стаб запроса - при вызове метода prepare
		$connectionMock->method('prepare')->willReturn($statementStub);
		// 1. Передаём в репозиторий стаб подключения
		$repository = new SqliteUsersRepository($connectionMock);
		// Ожидаем, что будет брошено исключение
		$this->expectException(UserNotFoundException::class);
		$this->expectExceptionMessage('Не могу получить пользователя: Login');
		// Вызываем метод получения пользователя
		$repository->getByUsername('Login');
	}

	public function testItSavesUserToDatabase(): void
	{
		// 2. Создаём стаб подключения
		$connectionStub = $this->createStub(PDO::class);
		// 4. Создаём мок запроса, возвращаемый стабом подключения
		$statementMock = $this->createMock(PDOStatement::class);
		// 5. Описываем ожидаемое взаимодействие
		// нашего репозитория с моком запроса
		$statementMock
			->expects($this->once()) // Ожидаем, что будет вызван один раз
			->method('execute') // метод execute
			->with([ // с единственным аргументом - массивом
				':uuid' => '123e4567-e89b-12d3-a456-426614174004',
				':last_name' => 'Nikitin',
				':username' => 'ivan123',
				':first_name' => 'Ivan',
			]);
		// 3. При вызове метода prepare стаб подключения
		// возвращает мок запроса
		$connectionStub->method('prepare')->willReturn($statementMock);
		// 1. Передаём в репозиторий стаб подключения
		$repository = new SqliteUsersRepository($connectionStub);
		// Вызываем метод сохранения пользователя
		$repository->save(
			new User( // Свойства пользователя точно такие,
			// как и в описании мока
				new UUID('123e4567-e89b-12d3-a456-426614174004'),
				new Name('Ivan', 'Nikitin'),
				'ivan123'
			)
		);
	}

	/**
	 * @throws UserNotFoundException
	 * @throws InvalidArgumentException
	 */
	public function testItGetUser(): void
	{
		$connectStub = $this->createStub(PDO::class);
		$statementMock = $this->createMock(PDOStatement::class);
		$statementMock->method('fetch')->willReturn([
			'uuid' => 'f2206efb-6e63-42a0-9f5d-196efa027725',
			'first_name' => 'Ivan',
			'last_name' => 'Suslov',
			'username' => 'Login',
		]);
		$connectStub->method('prepare')->willReturn($statementMock);
		$repository = new SqliteUsersRepository($connectStub);
		$user = $repository->get(new UUID('f2206efb-6e63-42a0-9f5d-196efa027725'));
		static::assertSame('f2206efb-6e63-42a0-9f5d-196efa027725', (string)$user->uuid());
	}

	public function testItGetUserByUuid():void
	{
		$connectStub = $this->createStub(PDO::class);
		$statementMock = $this->createMock(PDOStatement::class);
		$statementMock->method('fetch')->willReturn([
			'uuid' => 'f2206efb-6e63-42a0-9f5d-196efa027725',
			'first_name' => 'Ivan',
			'last_name' => 'Suslov',
			'username' => 'Login',
		]);
		$connectStub->method('prepare')->willReturn($statementMock);
		$repository = new SqliteUsersRepository($connectStub);
		$userId = $repository->getUUID(new UUID('f2206efb-6e63-42a0-9f5d-196efa027725'));
		static::assertSame('f2206efb-6e63-42a0-9f5d-196efa027725', (string)$userId->uuid());
	}
}