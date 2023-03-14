<?php

use GeekBrains\LevelTwo\Blog\Models\{Post, User, Comments};
use GeekBrains\LevelTwo\Person\{Name, Person};
use GeekBrains\LevelTwo\Blog\Repositories\InMemoryUsersRepository;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;

include __DIR__ . '/vendor/autoload.php';

$faker = Faker\Factory::create('ru_RU');

$name = new Name($faker->firstName(), $faker->lastName());
$user = new User(1, $name, 'Admin');
$person = new Person($name, new DateTimeImmutable());
$post = new Post(1, $person, $faker->title(), $faker->text(50));
$comment = new Comments(1, $person, $post, $faker->text(50));

switch ($argv[1]) {
  case 'user':
    print $user;
    break;
  case 'post':
    print $post;
    break;
  case 'comment':
    print $comment;
    break;
}

//$userRepository = new InMemoryUsersRepository();
//$userRepository->save($user);
//$userRepository->save(clone $user);
//
//try {
//  echo $userRepository->get(1);
//  echo $userRepository->get(2);
//  echo $userRepository->get(4);
//
//} catch (UserNotFoundException | Exception $e) {
//  echo $e->getMessage();
//}
