<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\CommentException\CommentNotFoundException;
use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\Exceptions\PostException\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserException\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Models\Comment;
use GeekBrains\LevelTwo\Blog\Repositories\Interface\CommentsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\UUID;
use PDO;
use PDOStatement;

class SqliteCommentsRepository implements CommentsRepositoryInterface
{
	public function __construct(
		private PDO $connection
	)
	{
	}

	public function save(Comment $comments): void
	{
		$statement = $this->connection->prepare(
			'INSERT INTO comments (uuid, post_uuid, author_uuid, text)
			VALUES (:uuid, :post_uuid, :author_uuid, :text)'
		);
		$statement->execute([
			':uuid' => $comments->getComment_uuid(),
			':post_uuid' => $comments->getPostUuid()->getPost_uuid(),
			':author_uuid' => $comments->getAuthorUuid()->uuid(),
			':text' => $comments->getTextComment(),
		]);
	}

	/**
	 * @param UUID $uuid
	 * @return Comment
	 * @throws CommentNotFoundException
	 * @throws InvalidArgumentException
	 * @throws PostNotFoundException
	 * @throws UserNotFoundException
	 */
	public function get(UUID $uuid): Comment
	{
		$statement = $this->connection
			->prepare('SELECT * FROM comments WHERE uuid = :uuid');
		$statement->execute([
			':uuid' => $uuid
		]);
		return $this->getComment($statement, $uuid);
	}

	/**
	 * @param PDOStatement $statement
	 * @param string $errorComment
	 * @return Comment
	 * @throws CommentNotFoundException
	 * @throws InvalidArgumentException
	 * @throws UserNotFoundException
	 * @throws PostNotFoundException
	 */
	public function getComment(PDOStatement $statement, string $errorComment): Comment
	{
		$result = $statement->fetch(PDO::FETCH_ASSOC);
		if ($result === false) {
			throw new CommentNotFoundException("Not comments: $errorComment\n");
		}
		$postRepository = new SqlitePostsRepository($this->connection);
		$commentsGetUser = new SqliteUsersRepository($this->connection);

		$commentPost = $postRepository->get(new UUID($result['post_uuid']));
		$commentUser = $commentsGetUser->get(new UUID($result['author_uuid']));

		return new Comment(
			new UUID($result['uuid']),
			$commentPost,
			$commentUser,
			$result['text']
		);
	}
}