<?php

use AdamWathan\Blog\PostRepositoryInterface;

class PostsController extends BaseController
{
	private $posts;

	public function __construct(PostRepositoryInterface $posts)
	{
		$this->posts = $posts;
	}

	public function showIndex()
	{
		$view = View::make('index');
		$view->posts = $this->posts->all();
		$view->title = 'Adam Wathan - Blog';
		return $view;
	}

	public function showPost($year, $month, $day, $slug)
	{
		$date = Carbon\Carbon::createFromDate($year, $month, $day);

		try {
			$post = $this->posts->byDateAndSlug($date, $slug);
		} catch (\Exception $e) {
			App::abort(404);
		}

		$view = View::make('post');
		$view->post = $post;
		$view->title = $post->title . ' : Adam Wathan';
		return $view;
	}

	public function rss()
	{
		$posts = $this->posts->paginate(100);
		return Response::view('rss', compact('posts'), 200, array(
			'Content-Type' => 'application/rss+xml; charset=UTF-8',
			));
	}
}
