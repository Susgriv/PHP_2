<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\PostsRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\Exceptions\PostException\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserException\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Models\Post;
use GeekBrains\LevelTwo\Blog\Repositories\Interface\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\UUID;
use PDO;
use PDOStatement;

class SqlitePostsRepository implements PostsRepositoryInterface
{
	public function __construct(
		private PDO $connection
	)
	{
	}

	public function save(Post $post): void
	{
		$statement = $this->connection->prepare(
			'INSERT INTO posts (uuid, author_uuid, title, text)
				VALUES (:uuid, :author_uuid, :title, :text)'
		);
		$statement->execute([
			':uuid' => $post->getPost_uuid(),
			':author_uuid' => $post->getAuthor(),
			':title' => $post->getTitle(),
			':text' => $post->getText(),
		]);
	}

	/**
	 * @throws UserNotFoundException|InvalidArgumentException|PostNotFoundException
	 */
	public function get(UUID $uuid): Post
	{
		$statement = $this->connection->prepare(
			'SELECT * FROM posts WHERE uuid = :uuid'
		);
		$statement->execute([
			':uuid' => (string)$uuid,
		]);
		return $this->getPost($statement, $uuid);
	}

	/**
	 * @throws PostNotFoundException|UserNotFoundException|InvalidArgumentException
	 */
	public function getPost(PDOStatement $statement, string $errorPost): Post
	{
		$result = $statement->fetch(PDO::FETCH_ASSOC);
		if ($result === false) {
			throw new PostNotFoundException("Failed to receive Post $errorPost\n");
		}
		$userRepository = new SqliteUsersRepository($this->connection);
		$user = $userRepository->get(new UUID($result['author_uuid']));

		return new Post(
			new UUID($result['uuid']),
			$user,
			$result['title'],
			$result['text']
		);
	}
}
