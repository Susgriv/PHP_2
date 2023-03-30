<?php

namespace Actions\Find;

use GeekBrains\LevelTwo\Blog\Exceptions\PostException\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Models\Post;
use GeekBrains\LevelTwo\Blog\Repositories\Interface\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Http\Actions\Users\FindByUuid;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\Request;
use PHPUnit\Framework\TestCase;

class FindByUuidActionTest extends TestCase
{
	// Запускаем тест в отдельном процессе
	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @throws \JsonException
	 */
	// Тест, проверяющий, что будет возвращён неудачный ответ, если в запросе нет параметра username
	public function testItReturnsErrorResponseIfNoUuidProvided(): void
	{
		// Создаём объект запроса вместо суперглобальных переменных передаём простые массивы
		$request = new Request([], [], '');
		// Создаём стаб репозитория пользователей
		$postsRepository = $this->postRepository([]);
		//Создаём объект действия
		$action = new FindByUuid($postsRepository);
		// Запускаем действие
		$response = $action->handle($request);
		// Проверяем, что ответ - неудачный
		static::assertInstanceOf(ErrorResponse::class, $response);
		// Описываем ожидание того, что будет отправлено в поток вывода
		$this->expectOutputString('{"success":false,"reason":"No such query param in the request: uuid"}');
		// Отправляем ответ в поток вывода
		$response->send();

	}

	// Функция, создающая стаб репозитория пользователей, принимает массив "существующих" пользователей
	public function postRepository(array $posts): PostsRepositoryInterface
	{
		return new class($posts) implements PostsRepositoryInterface {
			// В конструктор анонимного класса передаём массив пользователей
			public function __construct(
				private array $posts
			)
			{
			}

			public function save(Post $post): void
			{
				// TODO: Implement save() method.
			}

			public function get(UUID $author_uuid): Post
			{
				// TODO: Implement get() method.
				foreach ($this->posts as $post) {
					if ($post instanceof Post && $author_uuid === $post->getPost_uuid()) {
						return $post;
					}
//					throw new PostNotFoundException('Not found');
				}
				throw new PostNotFoundException('Not found');
			}

//			public function getPost(string $uuid): Post
//			{
//				foreach ($this->posts as $post){
//					if ($post instanceof Post && $uuid == $post->getPost_uuid()){
//						return $post;
//					}
//					throw new PostNotFoundException('Not found');
//				}
//			}
		};
	}
}