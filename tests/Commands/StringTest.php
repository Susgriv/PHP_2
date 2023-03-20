<?php

namespace GeekBrains\LevelTwo\Blog\UnitTest\Commands\Posts;

use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\Models\Comment;
use GeekBrains\LevelTwo\Blog\Models\Post;
use GeekBrains\LevelTwo\Blog\Models\User;
use GeekBrains\LevelTwo\Blog\Person\Name;
use GeekBrains\LevelTwo\Blog\UUID;
use PHPUnit\Framework\TestCase;

class StringTest extends TestCase
{
	/**
	 * @throws InvalidArgumentException
	 */
	public function testPostString(): void
	{
		$postUUID = UUID::random();
		$userUUID = UUID::random();
		$userName = new Name('first', 'last');
		$user = new User($userUUID, $userName, 'login');

		$post = new Post($postUUID, $user, 'title', 'text');
		$result = (string) $post;
		static::assertSame($postUUID . ' Заголовок: title
Юзер '. $userUUID.' с именем first last и логином login
пишет: text
', $result);
	}

	public function testCommentString(): void
	{
		$userUUID = UUID::random();
		$postUUID = UUID::random();
		$commentUUID = UUID::random();

		$userName = new Name('first', 'last');
		$user = new User($userUUID, $userName, 'login');
		$post = new Post($postUUID, $user, 'title', 'text');
		$comment = new Comment($commentUUID, $post, $user, 'Text');
		$this->assertSame("ID:$commentUUID Пользователь: Юзер $userUUID с именем first last и логином login
 Написал комментарий: Text к постуid$postUUID Заголовок: title
Юзер $userUUID с именем first last и логином login
пишет: text
", (string)$comment);
	}
}