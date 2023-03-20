<?php

namespace GeekBrains\LevelTwo\Blog\UnitTest\Commands\Posts;

use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\Exceptions\PostException\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserException\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Models\Post;
use GeekBrains\LevelTwo\Blog\Models\User;
use GeekBrains\LevelTwo\Blog\Person\Name;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\UUID;
use PDO;
use PDOStatement;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class SqlitePostsRepositoryTest extends TestCase
{
	// Выдает исключение, когда Post не найден

	/**
	 * @throws UserNotFoundException|InvalidArgumentException
	 */
	public function testItThrowsAnExceptionWhenPostNotFound(): void
	{
		// Создаем Стаб подключения
		$connectionStub = $this->createStub(PDO::class);

		// Создаем Стаб запроса
		$statementStub = $this->createStub(PDOStatement::class);

		// Стаб подключения будет возвращать другой стаб - стаб запроса - при вызове метода prepare
		$connectionStub->method('prepare')->willReturn($statementStub);

		// Стаб запроса будет возвращать false при вызове метода fetch
		$statementStub->method('fetch')->willReturn(false);

		// Создаем репозиторий и передаем в него Стаб подключения
		$repository = new SqlitePostsRepository($connectionStub);

		// Ожидаем что будет брошено исключение
		$this->expectException(PostNotFoundException::class);
		$this->expectExceptionMessage('Failed to receive Post');
		$repository->get(new UUID('f2206efb-6e63-4200-9f5d-196efa027725'));
	}

	/**
	 * @return void
	 * @throws Exception
	 */
	public function testItSavesPostToDatabase(): void
	{
		$connectionStub = $this->createStub(PDO::class);
		$statementMock = $this->createMock(PDOStatement::class);
		$statementMock->expects(
			static::once())
			->method('execute')
			->with([
				':uuid' => 'd2206efb-6e63-42a0-9f5d-196efa027725',
				':author_uuid' => 'f2206efb-6e63-42a0-9f5d-196efa027725',
				':title' => 'title',
				':text' => 'text',
			]);
		$connectionStub->method('prepare')->willReturn($statementMock);
		$repository = new SqlitePostsRepository($connectionStub);
		$user = new User(
			new UUID('f2206efb-6e63-42a0-9f5d-196efa027725'),
			new Name('first_name', 'last_name'),
			'username'
		);
		$repository->save(
			new Post(
				new UUID('d2206efb-6e63-42a0-9f5d-196efa027725'),
				$user,
				'title',
				'text',
			),
		);
	}
	//Получить сообщение по идентификатору
	/**
	 * @throws UserNotFoundException
	 * @throws Exception
	 * @throws PostNotFoundException
	 * @throws InvalidArgumentException
	 */
	public function testItGetPostByUuid(): void
	{
		// Сначала нам нужно подготовить все стабы
		// 2. Создаём стаб подключения
		$connectionStub = $this->createStub(PDO::class);
		// 4. Создаем Стаб запроса
		$statementMock = $this->createMock(PDOStatement::class);
		$statementMock->method('fetch')->willReturn([
			'uuid' => 'f2206efb-6e63-42a0-9f5d-196efa027725',
			'author_uuid' => 'f2206efb-6e63-42a0-9f5d-196efa027725',
			'title' => 'title',
			'text' => 'text',
			'first_name' => 'first',
			'last_name' => 'last',
			'username' => 'user',
		]);

		$connectionStub->method('prepare')->willReturn($statementMock);
		// 1. Передаём в репозиторий стаб подключения
		$postRepository = new SqlitePostsRepository($connectionStub);
		$post = $postRepository->get(new UUID('f2206efb-6e63-42a0-9f5d-196efa027725'));
		$this->assertSame('f2206efb-6e63-42a0-9f5d-196efa027725', (string)$post->getPost_uuid());
	}
}