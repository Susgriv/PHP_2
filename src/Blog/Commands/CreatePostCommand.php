<?php


namespace GeekBrains\LevelTwo\Blog\Commands;

use GeekBrains\LevelTwo\Blog\Exceptions\CommandException;
use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\Exceptions\PostException\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Models\Post;
use GeekBrains\LevelTwo\Blog\Repositories\Interface\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;

class CreatePostCommand
{
	public function __construct(
		private PostsRepositoryInterface $postRepository
	)
	{
	}

	/**
	 * @throws CommandException
	 */
	public function parseRawInput(array $rawInput): array
	{
		$input = [];
		foreach ($rawInput as $argument) {
			$parts = explode('=', $argument);
			if (count($parts) !== 2) {
				continue;
			}
			$input[$parts[0]] = $parts[1];
		}
		foreach (['author_uuid', 'title', 'text'] as $argument) {
			if (!array_key_exists($argument, $input)) {
				throw new CommandException("Не указан обязательный аргумент: $argument");
			}
			if (empty($input[$argument])) {
				throw new CommandException("Предоставлен пустой аргумент: $argument");
			}
		}
		return $input;
	}

	public function postExist(string $author): bool
	{
		try {
			$this->postRepository->getByPost($author);
		} catch (PostNotFoundException $ex) {
			return false;
		}
		return true;
	}

	/**
	 * @throws CommandException
	 * @throws InvalidArgumentException
	 */
	public function handle(array $rawInput): void
	{
		$input = $this->parseRawInput($rawInput);
		$author = $input['author_uuid'];
// Проверяем, существует ли Post в репозитории
		if ($this->postExist($author)) {
			// Бросаем исключение, если Post уже существует
			throw new CommandException("Сообщение уже существует: $author");
		}
		// Сохраняем Post в репозиторий
		$this->postRepository->save(new Post(
			UUID::random(),
			$author,
			$input['title'],
			$input['text']
		));
	}
}