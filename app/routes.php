<?php

Route::get('/', ['as' => 'home', 'uses' => 'PostsController@showIndex']);
Route::get('{year}/{month}/{day}/{slug}', ['as' => 'post', 'uses' => 'PostsController@showPost']);
Route::get('rss', ['as' => 'rss', 'uses' => 'PostsController@rss']);

Route::get('about', ['as' => 'about', function() {
    return View::make('about')->with('title', 'About : adamwathan.me');
}]);

Route::get('talks', ['as' => 'talks', function() {
	return View::make('talks')->with('title', 'Talks : adamwathan.me');
}]);

App::missing(function($exception) {
	return "404 Not Found";
});
