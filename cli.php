<?php

use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;

include __DIR__ . "/vendor/autoload.php";

//Создаём объект подключения к SQLite
$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

$usersRepository = new SqliteUsersRepository($connection);
$postsRepository = new SqlitePostsRepository($connection);

try {

$user = $usersRepository->get(new UUID('3b697686-01bf-433a-bf17-53ce84cb987b'));

$post = $postsRepository->get(new UUID('fb58c755-9413-4945-b1d7-b2bd1979ae34'));

print_r($post);

} catch (Exception $e) {
    echo $e->getMessage();
}
/*
$command = new CreateUserCommand($usersRepository);

try {
    $command->handle(Arguments::fromArgv($argv));
} catch (Exception $e) {
    echo $e->getMessage();
}*/