<?php namespace Blog;

use File;
use \Michelf\MarkdownExtra;
use AdamWathan\MetaMarkdown\MetaMarkdown;

class Post
{
	public function __construct()
	{
	}

	public function __toString()
	{
		return $this->render();
	}

	public function render()
	{
		if ($this->isExpired()) {
			$this->compile();
		}

		return File::get($this->cachePath());
	}

	public function isExpired()
	{
		if (! $this->isCompiled()) {
			return true;
		}

		$cacheLastModified = File::lastModified($this->cachePath());

		return File::lastModified($this->fullPath()) >= $cacheLastModified;
	}

	public function compile()
	{
		$result = MarkdownExtra::defaultTransform(File::get($this->fullPath()));
		File::put($this->cachePath(), $result);
	}

	protected function cachePath()
	{
		return storage_path().'/posts/'.md5($this->path);
	}

	protected function fullPath()
	{
		return app_path().'/posts/'.$this->path;
	}

	protected function isCompiled()
	{
		return File::exists($this->cachePath());
	}
}