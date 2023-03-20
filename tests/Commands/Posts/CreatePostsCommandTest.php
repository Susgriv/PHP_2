<?php
//
//namespace GeekBrains\LevelTwo\Blog\UnitTest\Commands\Posts;
//
//use GeekBrains\LevelTwo\Blog\Commands\Arguments;
//use GeekBrains\LevelTwo\Blog\Commands\CreatePostCommand;
//use GeekBrains\LevelTwo\Blog\Commands\CreateUserCommand;
//use GeekBrains\LevelTwo\Blog\Exceptions\CommandException;
//use GeekBrains\LevelTwo\Blog\Exceptions\PostException\PostNotFoundException;
//use GeekBrains\LevelTwo\Blog\Models\Post;
//use GeekBrains\LevelTwo\Blog\Repositories\Interface\PostsRepositoryInterface;
//use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\DummyPostsRepository;
//use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\DummyUsersRepository;
//use PHPUnit\Framework\TestCase;
//
//class CreatePostsCommandTest extends TestCase
//{
//	public function testItThrowsAnExceptionWhenPostAlreadyExists(): void
//	{
//		$command = new CreatePostCommand(new DummyPostsRepository());
//		$this->expectException(CommandException::class);
//		$this->expectExceptionMessage('Не удалось получить Post');
//		$command->handle(new Arguments([
//			'first_name' => 'Ivan',
//			'last_name' => 'Suslov',
//			'username' => 'Login',
//			'author_uuid' => 'f2206efb-6e63-42a0-9f5d-196efa027725'
//		]));
//	}
//
//	public function testItPostsUserToRepository()
//	{
//		$postsRepository = new class implements PostsRepositoryInterface{
//			private bool $called = false;
//			public function save(Post $post): void
//			{
//				$this->called = true;
//			}
//
//			public function get(string $author_uuid): Post
//			{
//				throw new PostNotFoundException('Не удалось получить Post');
//			}
//			public function wasCalled(): bool
//			{
//				return $this->called;
//			}
//		};
//		$command = new CreateUserCommand($postsRepository);
//		$command->handle(new Arguments([
//			'first_name' => 'Ivan',
//			'last_name' => 'Suslov',
//			'username' => 'Login',
//			''
//		]));
//	}
//}