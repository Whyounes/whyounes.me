<?php

Route::get('/', ['as' => 'home', 'uses' => 'PostsController@showIndex']);
Route::get('{year}/{month}/{day}/{slug}', ['as' => 'post', 'uses' => 'PostsController@showPost']);
Route::get('archives', ['as' => 'archives', 'uses' => 'PostsController@showArchives']);
Route::get('rss', ['as' => 'rss', 'uses' => 'PostsController@rss']);

Route::get('about', ['as' => 'about', function() {
	return View::make('about')->with('title', 'About : adamwathan.me');
}]);

App::missing(function($exception) {
	return "404 Not Found";
});