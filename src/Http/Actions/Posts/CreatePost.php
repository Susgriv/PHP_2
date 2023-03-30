<?php

namespace GeekBrains\LevelTwo\Http\Actions\Posts;


use GeekBrains\LevelTwo\Blog\Exceptions\HttpException\HttpException;
use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserException\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Models\Post;
use GeekBrains\LevelTwo\Blog\Repositories\Interface\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\Interface\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;

class CreatePost implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private UsersRepositoryInterface $usersRepository,
    )
    {
    }

    public function handle(Request $request): Response
    {
        // Пытаемся создать UUID пользователя из данных запроса
        try {
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
        // Генерируем UUID для новой статьи
        $newPostUuid = UUID::random();
        // Пытаемся создать объект статьи из данных запроса
        try {
            $post = new Post(
                $newPostUuid,
                $authorUuid,
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text'),
            );
        } catch (HttpException $exception) {
            return new ErrorResponse($exception->getMessage());
        }
        $this->postsRepository->save($post);
        return new SuccessfulResponse([
            'post id' => (string)$newPostUuid,
	        'author id' => (string)$authorUuid,
        ]);
    }
}