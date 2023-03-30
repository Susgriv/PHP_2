<?php

namespace Actions\Create;

use GeekBrains\LevelTwo\Blog\Exceptions\PostException\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Models\Post;
use GeekBrains\LevelTwo\Blog\Repositories\Interface\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;
use PHPUnit\Framework\TestCase;

class CreatePostsTest extends TestCase
{
	public function makePostRepository(): PostsRepositoryInterface
	{
		return new class implements PostsRepositoryInterface {
			public function save(Post $post): void
			{
			}

			public function get(UUID $author_uuid): Post
			{
				throw new PostNotFoundException('Not found Post');
			}
		};
	}

	//класс возвращает успешный ответ
	public function testReturnsSuccessfulResponse(): void
	{

	}

	//возвращает ошибку, если запрос содержит UUID в неверном формате
	public function testReturnsAnErrorIfTheRequestContainsUUIDInWrongFormat(): void
	{

	}

	//возвращает ошибку, если пользователь не найден по этому UUID
	public function testReturnsAnErrorIfTheUserIsNotFoundByThisUUID(): void
	{

	}

	//возвращает ошибку, если запрос не содержит всех данных, необходимых для создания статьи
	public function testReturnsAnErrorIfTheRequestDoesNotContainAllTheDataRequiredForArticleCreation(): void
	{

	}

	// Функция, создающая стаб репозитория статей, принимает массив "существующих" статей
	public function postsRepository(array $posts): PostsRepositoryInterface
	{
		// В конструктор анонимного класса передаём массив статей
		return new class($posts) implements PostsRepositoryInterface {

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
				foreach ($this->posts as $post) {
					if ($post instanceof Post && $post === $author_uuid->uuid()){
						return $post;
					}
				}
				throw new PostNotFoundException('Not found posts');
			}
		};
	}
}