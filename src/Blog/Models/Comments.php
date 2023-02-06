<?php

namespace GeekBrains\LevelTwo\Blog\Models;

use GeekBrains\LevelTwo\Person\Person;

class Comments
{
  public function __construct(
    private int $id,
    private Person $author,
    private Post $idPost,
    private string $text)
  {
  }

public function __toString() {
    return 'ID:' . $this->id . ' ' . 'Пользователь:' . ' ' . $this->author .
      ' ' . 'Написал комментарий:' . ' '  . $this->text . ' ' . 'к посту' .
      'id' . $this->idPost;
}
}