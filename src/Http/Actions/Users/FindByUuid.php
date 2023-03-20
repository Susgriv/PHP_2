<?php

namespace GeekBrains\LevelTwo\Http\Actions\Users;

use GeekBrains\LevelTwo\Blog\Exceptions\HttpException\HttpException;
use GeekBrains\LevelTwo\Blog\Exceptions\PostException\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Models\Post;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Blog\Repositories\Interface\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;

class FindByUuid implements ActionInterface
{
	public function __construct(
		private PostsRepositoryInterface $postsRepository
	)
	{
	}

	// Функция, описанная в контракте
	public function handle(Request $request): Response
	{
		try {
			// Пытаемся получить искомый UUID из запроса
			$uuid = $request->query('uuid');
		} catch (HttpException $e) {
			// Если в запросе нет параметра uuid - возвращаем неуспешный ответ,
			// сообщение об ошибке берём из описания исключения
			return new ErrorResponse($e->getMessage());
		}
		try {
			// Пытаемся найти uuid в репозитории
			$post = $this->postsRepository->get(new UUID($uuid));
		} catch (PostNotFoundException $e) {
			// Если uuid не найден - возвращаем неуспешный ответ
			return new ErrorResponse($e->getMessage());
		}
		// Возвращаем успешный ответ
		return new SuccessfulResponse([
			'ID' => $post->getPost_uuid()->getUuid(),
			'Author' => $post->getAuthor()->uuid()->getUuid(),
			'title' => $post->getTitle(),
			'text' => $post->getText(),
		]);
	}
}