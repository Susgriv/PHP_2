<?php

namespace GeekBrains\LevelTwo\Blog\UnitTest\Commands\Users;

use GeekBrains\LevelTwo\Blog\Commands\Arguments;
use GeekBrains\LevelTwo\Blog\Commands\CreateUserCommand;
use GeekBrains\LevelTwo\Blog\Exceptions\ArgumentsException;
use GeekBrains\LevelTwo\Blog\Exceptions\CommandException;
use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserException\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Models\User;
use GeekBrains\LevelTwo\Blog\Person\Name;
use GeekBrains\LevelTwo\Blog\Repositories\Interface\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;
use PHPUnit\Framework\TestCase;

class CreateUsersCommandTest extends TestCase
{
//	 Проверяем, что команда создания пользователя бросает исключение,
//	 если пользователь с таким именем уже существует
	public function testItThrowsAnExceptionWhenUserAlreadyExists(): void
	{
		// Создаём объект команды
		// У команды одна зависимость - UsersRepositoryInterface
//		$commands = new CreateUserCommand(
//		// Передаём наш стаб в качестве реализации UsersRepositoryInterface
//			new DummyUsersRepository()
//		);
		$commandsRepository = new class implements UsersRepositoryInterface {

			public function save(User $user): void
			{
				// TODO: Implement save() method.
			}

			public function get(UUID $UUID): User
			{
				// TODO: Implement get() method.
				throw new CommandException('User already exists:');
			}

			public function getByUsername(string $username): User
			{
				// TODO: Implement getByUsername() method.
				return new User(new UUID('d2206efb-6e63-42a0-9f5d-196efa027725'), new Name('first', 'last'), 'Admin');
			}
		};
		$commands = new CreateUserCommand($commandsRepository);
		// Описываем тип ожидаемого исключения
//		$this->expectException(ArgumentsException::class);
		$this->expectException(CommandException::class);
		// и его сообщение
		$this->expectExceptionMessage('User already exists: user123');
		// Запускаем команду с аргументами
		$commands->handle(new Arguments(['username' => 'user123']));
	}

	//Тест проверяет, что команда действительно требует имя пользователя
//	public function testItRequiresFirstName(): void
//	{
//		// $usersRepository - это объект анонимного класса,
//		// реализующего контракт UsersRepositoryInterface
//		$usersRepository = new class implements UsersRepositoryInterface {
//			public function save(User $user): void
//			{
//				// Ничего не делаем
//			}
//
//			public function get(UUID $uuid): User
//			{
//				// И здесь ничего не делаем
//				throw new UserNotFoundException('Not found');
//			}
//
//			public function getByUsername(string $username): User
//			{
//				// И здесь ничего не делаем
//				throw new UserNotFoundException('Not found');
//			}
//		};
//		// Передаём объект анонимного класса
//		// в качестве реализации UsersRepositoryInterface
//		$command = new CreateUserCommand($usersRepository);
//		// Ожидаем, что будет брошено исключение
//		$this->expectException(ArgumentsException::class);
//		$this->expectExceptionMessage('No such argument: first_name');
//		// Запускаем команду
//		$command->handle(new Arguments(['username' => 'Login']));
//	}

// Функция возвращает объект типа UsersRepositoryInterface
	public function makeUsersRepository(): UsersRepositoryInterface
	{
		return new class implements UsersRepositoryInterface {
			public function save(User $user): void
			{
			}

			public function get(UUID $UUID): User
			{
				throw new UserNotFoundException('Not found');
			}

			public function getByUsername(string $username): User
			{
				throw new UserNotFoundException('Not found');
			}
		};
	}

	// Тест проверяет, что команда действительно требует фамилию пользователя

	/**
	 * @throws CommandException
	 * @throws InvalidArgumentException
	 */
	public function testItRequiresLastName(): void
	{
		// Передаём в конструктор команды объект, возвращаемый нашей функцией
		$command = new CreateUserCommand($this->makeUsersRepository());
		$this->expectException(ArgumentsException::class);
		$this->expectExceptionMessage('No such argument: last_name');
		$command->handle(new Arguments([
			'username' => 'Login',
			// Нам нужно передать имя пользователя,
			// чтобы дойти до проверки наличия фамилии
			'first_name' => 'Ivan',
		]));
	}

	// Тест проверяет, что команда действительно требует имя пользователя
	public function testItRequiresFirstName(): void
	{
		$command = new CreateUserCommand($this->makeUsersRepository());
		$this->expectException(ArgumentsException::class);
		$this->expectExceptionMessage('No such argument: first_name');
		$command->handle(new Arguments([
			'username' => 'Login',
		]));
	}

	// Тест, проверяющий, что команда сохраняет пользователя в репозитории MOK

	/**
	 * @throws ArgumentsException
	 * @throws CommandException
	 * @throws InvalidArgumentException
	 */
	public function testItSavesUserToRepository(): void
	{
		// Создаём объект анонимного класса
		$usersRepository = new class implements UsersRepositoryInterface {
			// В этом свойстве мы храним информацию о том,
			// был ли вызван метод save
			private bool $called = false;

			public function save(User $user): void
			{
				// Запоминаем, что метод save был вызван
				$this->called = true;
			}

			public function get(UUID $uuid): User
			{
				throw new UserNotFoundException('Not found');
			}

			public function getByUsername(string $username): User
			{
				throw new UserNotFoundException('Not found');
			}
			// Этого метода нет в контракте UsersRepositoryInterface,
			// но ничто не мешает его добавить.
			// С помощью этого метода мы можем узнать,
			// был ли вызван метод save
			public function wasCalled(): bool
			{
				return $this->called;
			}
		};
		// Передаём наш мок в команду
		$command = new CreateUserCommand($usersRepository);
		// Запускаем команду
		$command->handle(new Arguments([
			'first_name' => 'Ivan',
			'last_name' => 'Suslov',
			'username' => 'Login',
		]));
		// Проверяем утверждение относительно мока,
		// а не утверждение относительно команды
		$this->assertTrue($usersRepository->wasCalled());
	}
}
