<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\Interface;

use GeekBrains\LevelTwo\Blog\Models\User;
use GeekBrains\LevelTwo\Blog\UUID;

interface UsersRepositoryInterface
{
  public function save(User $user): void;
  public function get(UUID $UUID): User;
  public function getByUsername(string $username): User;
}