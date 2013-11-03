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
		$view->posts = $this->posts->paginate(1);
		$view->title = 'adamwathan.me';
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
		$view->title = $post->title . ' : adamwathan.me';
		return $view;
	}

	public function showArchives()
	{
		$view = View::make('archives');
		$view->posts = $this->posts->all();
		$view->title = 'Archives : adamwathan.me';
		return $view;
	}
}