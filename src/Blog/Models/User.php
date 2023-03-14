<?php
// Пользователь
namespace GeekBrains\LevelTwo\Blog\Models;

use GeekBrains\LevelTwo\Person\Name;

class User
{
  private int $id;
  private Name $username;
  private string $login;

  public function __construct(int $id, Name $username, string $login)
  {
    $this->id = $id;
    $this->username = $username;
    $this->login = $login;
  }
  public function __toString()
  {
    return "Юзер $this->id с именем $this->username и логином $this->login" . PHP_EOL;
  }

  public function id(): int
  {
    return $this->id;
  }

  public function setId(int $id): void
  {
    $this->id = $id;
  }

  public function getUsername(): Name
  {
    return $this->username;
  }

  public function setUsername(Name $username): void
  {
    $this->username = $username;
  }

  public function getLogin(): string
  {
    return $this->login;
  }

  public function setLogin(string $login): void
  {
    $this->login = $login;
  }
}