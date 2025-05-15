<?php

use app\controllers\ApiExampleController;
use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */
$router->get('/', \app\controllers\HomeController::class . '->index')->setAlias('home');

$router->get('/hello-world/@name', function($name) {
	echo '<h1>Hello world! Oh hey '.$name.'!</h1>';
});

$router->group('/api', function() use ($router, $app) {
	$Api_Example_Controller = new ApiExampleController($app);
	$router->get('/users', [ $Api_Example_Controller, 'getUsers' ]);
	$router->get('/users/@id:[0-9]', [ $Api_Example_Controller, 'getUser' ]);
	$router->post('/users/@id:[0-9]', [ $Api_Example_Controller, 'updateUser' ]);
});

// Blog
$router->group('/blog', function(Router $router) {
    // Posts
    $router->get('', \app\controllers\PostController::class . '->index')->setAlias('blog');
    $router->get('/create', \app\controllers\PostController::class . '->create');
    $router->post('', \app\controllers\PostController::class . '->store');
    $router->get('/@id', \app\controllers\PostController::class . '->show');
    $router->get('/@id/edit', \app\controllers\PostController::class . '->edit')->setAlias('blog_edit_method_get');
    $router->post('/@id/edit', \app\controllers\PostController::class . '->update')->setAlias('blog_edit_method_post');
    $router->get('/@id/delete', \app\controllers\PostController::class . '->destroy');
    // Comments
    $router->post('/@id/comment', \app\controllers\CommentController::class . '->store');
    $router->get('/@id/comment/@comment_id/delete', \app\controllers\CommentController::class . '->destroy');
});
