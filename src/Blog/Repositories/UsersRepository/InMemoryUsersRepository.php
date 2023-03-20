<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\UsersRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\UserException\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Models\User;
use GeekBrains\LevelTwo\Blog\Repositories\Interface\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;

class InMemoryUsersRepository implements UsersRepositoryInterface
{
  /**
   * @var User[]
   */
  private array $users = [];

  /**
   * @param User $user
   */
  public function save(User $user): void
  {
    $this->users[] = $user;
  }

  /**
   * @param int $id
   * @return User
   * @throws UserNotFoundException
   */
  public function get(UUID $id): User
  {
    foreach ($this->users as $user) {
      if ((string)$user->uuid() === (string)$id) {
        return $user;
      }
    }
    throw new UserNotFoundException("User not found: $id");
  }

  // Добавили метод получения пользователя по username

  /**
   * @throws UserNotFoundException
   */
  public function getByUsername(string $username): User
  {
    // TODO: Implement getByUsername() method.
    foreach ($this->users as $user){
      if ($user->username() === $username){
        return $user;
      }
    }
    throw new UserNotFoundException("User not found: $username");
  }
}