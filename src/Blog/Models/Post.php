<?php

namespace GeekBrains\LevelTwo\Blog\Models;

use GeekBrains\LevelTwo\Person\Person;

class Post
{
  public function __construct(
    private int    $id,
    private Person $author,
    private string $title,
    private string $text
  )
  {
  }

  public function __toString()
  {
    return $this->id . ' ' . 'Заголовок:' . ' ' . $this->title . PHP_EOL .
      $this->author . 'пишет: ' . $this->text . PHP_EOL;
  }
}