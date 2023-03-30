<?php


use GeekBrains\LevelTwo\Blog\Exceptions\AppException;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException\HttpException;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Http\Actions\Comments\CreateComment;
use GeekBrains\LevelTwo\Http\Actions\Posts\CreatePost;
use GeekBrains\LevelTwo\Http\Actions\Users\CreateUser;
use GeekBrains\LevelTwo\Http\Actions\Users\FindByUsername;
use GeekBrains\LevelTwo\Http\Actions\Users\FindByUuid;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\Request;

require_once __DIR__ . '/vendor/autoload.php';

// Создаём объект запроса из суперглобальных переменных
$request = new Request($_GET, $_SERVER, file_get_contents('php://input'));
try {
	// Пытаемся получить путь из запроса
	$path = $request->path();
} catch (HttpException) {
	// Отправляем неудачный ответ, если по какой-то причине не можем получить путь
	(new ErrorResponse)->send();
	// Выходим из программы
	return;
}

try {
	// Пытаемся получить HTTP-метод запроса
	$method = $request->method();
} catch (HttpException) {
	// Возвращаем неудачный ответ, если по какой-то причине не можем получить метод
	(new ErrorResponse)->send();
	return;
}

$routes = [
	// Создаём действие, соответствующее пути /users/show
	'GET' => [
		'/users/show' => new FindByUsername(
		// Действию нужен репозиторий
			new SqliteUsersRepository(
				new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
			),
		),
		//	 Второй маршрут
		'/posts/show' => new FindByUuid(
			new SqlitePostsRepository(
				new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
			),
		)],
	'POST' => [
		'/users/create' => new CreateUser(
			new SqliteUsersRepository(
				new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
			),
		),
		'/posts/create' => new CreatePost(
			new SqlitePostsRepository(
				new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
			),
			new SqliteUsersRepository(
				new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
			)
		),
		'/comments/create' => new CreateComment(
			new SqlitePostsRepository(
				new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
			),
			new SqliteUsersRepository(
				new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
			),
			new SqliteCommentsRepository(
				new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
			)
		),
	],
];

// Если у нас нет маршрута для пути из запроса - отправляем неуспешный ответ
if (!array_key_exists($method, $routes)) {
	(new ErrorResponse('Not found routes'))->send();
	return;
}

// Ищем маршрут среди маршрутов для этого метода
if (!array_key_exists($path, $routes[$method])) {
	(new ErrorResponse('Not found path'))->send();
	return;
}

// Выбираем действие по методу и пути
$action = $routes[$method][$path];

try {
	$response = $action->handle($request);
	$response->send();
} catch (AppException $exception) {
	(new ErrorResponse($exception->getMessage()))->send();
}
