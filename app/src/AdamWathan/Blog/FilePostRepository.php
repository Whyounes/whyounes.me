<?php namespace AdamWathan\Blog;

use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Cache\CacheManager;
use Illuminate\Pagination\Environment as Paginator;

class FilePostRepository implements PostRepositoryInterface
{
	private $path;
	private $compiler;
	private $filesystem;
	private $posts;
	private $cache;
	private $paginator;

	public function __construct($path, PostCompilerInterface $compiler, Filesystem $filesystem, CacheManager $cache, Paginator $paginator)
	{
		$this->path = $path;
		$this->compiler = $compiler;
		$this->filesystem = $filesystem;
		$this->cache = $cache;
		$this->paginator = $paginator;
		$this->loadPosts();
	}

	private function loadPosts()
	{
		$posts = array();

		foreach($this->filesystem->allFiles($this->path) as $post) {
			$post = $this->loadPost($post->getRelativePathname());
			$posts[md5($post->date . $post->slug)] = $post;
		}

		$this->posts = with(new Collection($posts))->sort(function ($a, $b) {
			if ($a->date == $b->date) {
				return 0;
			}
			return ($a->date > $b->date) ? -1 : 1;
		});
	}

	private function loadPost($path)
	{
		if ($this->hasValidCached($path)) {
			return $this->getCached($path);
		}

		return $this->makePost($path);
	}

	private function hasValidCached($path)
	{
		if ( ! $this->cache->has($path)) {
			return false;
		}

		$post = $this->getCached($path);

		if ($post->lastModified < $this->lastModified($path)) {
			$this->cache->forget($path);
			return false;
		}

		return true;
	}

	private function getCached($path)
	{
		return $this->cache->get($path);
	}

	private function lastModified($path)
	{
		return $this->filesystem->lastModified($this->path.'/'.$path);
	}

	public function all()
	{
		return $this->posts;
	}

	public function paginate($perPage)
	{
		$currentPage = $this->paginator->getCurrentPage();

		$start = ($currentPage - 1) * $perPage;
		$posts = $this->posts->slice($start, $perPage);
		$total = $this->posts->count();

		return $this->paginator->make($posts->toArray(), $total, $perPage);
	}

	public function newest()
	{
		return $this->posts->first();
	}

	public function byDateAndSlug($date, $slug)
	{
		$post = $this->posts[md5($date->toDateString() . $slug)];

		return $post;
	}

	private function makePost($path)
	{
		$file = $this->filesystem->get($this->path.'/'.$path);
		$post = $this->compiler->compile($file);
		$post->lastModified = $this->lastModified($path);
		$this->cache->forever($path, $post);

		return $post;
	}
}