<?php

namespace GeekBrains\LevelTwo\Commands;

use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\UUID;
use PHPUnit\Framework\TestCase;

class UuidTest extends TestCase
{
//	public function testItUuidNotValidToException(): void
//	{
//		$id = UUID::random();
//		$this->expectException(InvalidArgumentException::class);
//		$this->expectExceptionMessage('Уродливый UUID:');
//		$test = uuid_is_valid($id);
//		$this->assertSame($id, $test);
//	}
}