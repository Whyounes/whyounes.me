<?php

use Blog\PostRepositoryInterface;

class PostsController extends BaseController
{
	private $posts;

	public function __construct(PostRepositoryInterface $posts)
	{
		$this->posts = $posts;
		$this->beforeFilter('@hasPermission');
	}

	protected function hasPermission()
	{	
		$id = Route::input('id');
		if ($id > 5) {
			return $id;
		}
	}

	public function test($id)
	{
		return "test";
	}

	public function showIndex()
	{
		return 'here';
	}

}