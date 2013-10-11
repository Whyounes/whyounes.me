<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

use Blog\FilePostRepository;
use Blog\MarkdownPostCompiler;

App::bind('Blog\PostCompilerInterface', 'Blog\MarkdownPostCompiler');
App::bind('Blog\PostRepositoryInterface', 'Blog\FilePostRepository');

// App::bind('Blog\PostRepositoryInterface', function($app) {


// 	// This works
// 	// $repository = new FilePostRepository(
// 	// 	$app->make('Blog\PostCompilerInterface'),
// 	// 	$app['files'],
// 	// 	$app['cache']
// 	// 	);

// 	// This doesn't
// 	$repository = $app->make('Blog\FilePostRepository');
// 	$repository->setPath(app_path().'/posts/');

// 	return $repository;
// });

use Blog\Post;

Route::get('/', 'PostsController@showIndex');
Route::get('test/{id}', 'PostsController@test');