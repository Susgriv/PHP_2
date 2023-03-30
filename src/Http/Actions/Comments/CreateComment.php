<?php

namespace GeekBrains\LevelTwo\Http\Actions\Comments;

use GeekBrains\LevelTwo\Blog\Exceptions\HttpException\HttpException;
use GeekBrains\LevelTwo\Blog\Exceptions\PostException\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserException\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Models\Comment;
use GeekBrains\LevelTwo\Blog\Models\Post;
use GeekBrains\LevelTwo\Blog\Repositories\Interface\CommentsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\Interface\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\Interface\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use PharIo\Manifest\Author;

class CreateComment implements ActionInterface
{
	public function __construct(
		private PostsRepositoryInterface    $postsRepository,
		private UsersRepositoryInterface    $usersRepository,
		private CommentsRepositoryInterface $commentsRepository
	)
	{
	}

	/**
	 * @throws HttpException
	 */
	public function handle(Request $request): Response
	{
		try {
			// Пытаемся создать UUID USER из данных запроса
			$authorUuid = new UUID($request->jsonBodyField('author_uuid'));
		} catch (HttpException|InvalidArgumentException $exception) {
			return new ErrorResponse($exception->getMessage());
		}
		// Пытаемся найти пользователя в репозитории
		try {
			$this->usersRepository->get($authorUuid);
		} catch (UserNotFoundException $exception) {
			return new ErrorResponse($exception->getMessage());
		}
		try {
			// Пытаемся создать UUID POST из данных запроса
			$postId = new UUID($request->jsonBodyField('post_uuid'));
		} catch (HttpException|InvalidArgumentException $exception) {
			return new ErrorResponse($exception->getMessage());
		}
		try {
			// Пытаемся найти POST в репозитории
			$this->postsRepository->get($postId);
		} catch (PostNotFoundException $exception) {
			return new ErrorResponse($exception->getMessage());
		}

		$post = new Post(
			$postId,
			$authorUuid,
			'sdafgf',
			'asasd'
		);
		try {
			$newComment = UUID::random();
			// Пытаемся создать объект комментария из данных запроса
			$comment = new Comment(
				$newComment,
				$post,
				$authorUuid,
				$request->jsonBodyField('text'),
			);
		} catch (HttpException $exception) {
			return new ErrorResponse($exception->getMessage());
		}
		$this->commentsRepository->save($comment);
		return new SuccessfulResponse([
			'post id' => (string)$newComment,
		]);

	}
}