<?php

namespace Commands\Comments;

use GeekBrains\LevelTwo\Blog\Exceptions\CommandException;
use GeekBrains\LevelTwo\Blog\Exceptions\CommentException\CommentNotFoundException;
use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\Exceptions\PostException\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserException\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Models\Comment;
use GeekBrains\LevelTwo\Blog\Models\Post;
use GeekBrains\LevelTwo\Blog\Models\User;
use GeekBrains\LevelTwo\Blog\Person\Name;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use GeekBrains\LevelTwo\Blog\UUID;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class SqliteCommentsRepositoryTest extends TestCase
{
	/**
	 * @throws UserNotFoundException
	 * @throws PostNotFoundException
	 * @throws CommentNotFoundException
	 * @throws InvalidArgumentException
	 */
	public function testItThrowsAnExceptionWhenCommentNotFound(): void
	{
		$connection = $this->createStub(\PDO::class);
		$statement = $this->createStub(\PDOStatement::class);

		$statement->method('fetch')->willReturn(false);
		$connection->method('prepare')->willReturn($statement);

		$repository = new SqliteCommentsRepository($connection);

		$this->expectException(CommentNotFoundException::class);
		$this->expectExceptionMessage('Not comments:');

		$repository->get(new UUID('33c3bdb6-2a40-4c1b-8605-ee98d555d313'));
	}

	public function testItSavesToDatabase(): void
	{
		$connectionStub = $this->createStub(PDO::class);
		$statementMock = $this->createMock(PDOStatement::class);
		$statementMock->expects(
			static::once())
			->method('execute')
			->with([
				':uuid' => '0a85dbb9-bad8-4193-8f2c-0d52eabd01fc',
				':post_uuid' => '3f6a9a55-a870-452d-844d-8f52f8336600',
				':author_uuid' => 'f2206efb-6e63-42a0-9f5d-196efa027725',
				':text' => 'Some_text',
			]);
		$connectionStub->method('prepare')->willReturn($statementMock);
		$repository = new SqliteCommentsRepository($connectionStub);
		$user = new User(
			new UUID('f2206efb-6e63-42a0-9f5d-196efa027725'),
			new Name('first_name', 'last_name'),
			'username'
		);
		$post = new Post(
			new UUID('3f6a9a55-a870-452d-844d-8f52f8336600'),
			$user,
			'Some_text',
			'Some_text',
		);
		$repository->save(
			new Comment(
				new UUID('0a85dbb9-bad8-4193-8f2c-0d52eabd01fc'),
				$post,
				$user->uuid(),
				'Some_text',
			)
		);
	}

	/**
	 * @throws UserNotFoundException
	 * @throws CommentNotFoundException
	 * @throws PostNotFoundException
	 * @throws InvalidArgumentException
	 */
	public function testItGetCommentsOnUuid(): void
	{
		$connectionStub = $this->createStub(\PDO::class);
		$statementMock = $this->createMock(\PDOStatement::class);
		$statementMock->method('fetch')->willReturn([
			'uuid' => '33c3bdb6-2a40-4c1b-8605-ee98d555d311',
			'post_uuid' => '33c3bdb6-2a40-4c1b-8605-ee98d555d312',
			'author_uuid' => '33c3bdb6-2a40-4c1b-8605-ee98d555d313',
			'text' => 'text',
			'title' => 'title',
			'first_name' => 'first',
			'last_name' => 'last',
			'username' => 'user',
		]);
		$connectionStub->method('prepare')->willReturn($statementMock);
		$repository = new SqliteCommentsRepository($connectionStub);
		$comments = $repository->get(new UUID('33c3bdb6-2a40-4c1b-8605-ee98d555d311'));
		$this->assertSame('33c3bdb6-2a40-4c1b-8605-ee98d555d311', (string)$comments->getComment_uuid());
	}

//	public function testPost(): void
//	{
//
//	}
}