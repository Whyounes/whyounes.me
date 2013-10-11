<?php namespace Blog;

use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Cache\CacheManager;

class FilePostRepository implements PostRepositoryInterface
{
	private $path;
	private $compiler;
	private $filesystem;
	private $posts;
	private $cache;

	public function __construct(PostCompilerInterface $compiler, Filesystem $filesystem, CacheManager $cache)
	{
		$this->compiler = $compiler;
		$this->filesystem = $filesystem;
		$this->cache = $cache;
	}

	public function setPath($path)
	{
		$this->path = $path;
	}

	private function loadPosts()
	{
		$posts = array();

		foreach($this->filesystem->allFiles($this->path) as $post) {
			$posts[] = $post->getRelativePathname();
		}

		return new Collection($posts);
	}

	public function all()
	{
		return $this->posts;
	}

	public function newest()
	{
		return $this->makePost($this->posts->last());
	}

	public function bySlug($slug)
	{
		throw new \Exception('Not yet implemented');
	}

	private function makePost($path)
	{
		$path = $this->path . $path;
		$markdown = new MetaMarkdown($path);
		$post = new Post;
		$post->title = $markdown->title;
		$post->slug = $markdown->slug;
		$post->date = $markdown->date;
		$post->content = $markdown->html;
		return $post;
	}
}