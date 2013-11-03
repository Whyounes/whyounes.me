<?php namespace AdamWathan\Blog;

use File;
use \Michelf\MarkdownExtra;
use AdamWathan\MetaMarkdown\MetaMarkdown;

class Post
{
	public $html;

	public function __toString()
	{
		return $this->render();
	}

	public function render()
	{
		return $this->html;
	}
}