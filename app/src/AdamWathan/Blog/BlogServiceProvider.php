<?php namespace AdamWathan\Blog;

use Illuminate\Support\ServiceProvider;

class BlogServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerPostsPath();
		$this->registerPostCompiler();
		$this->registerPostRepository();
	}

	protected function registerPostsPath()
	{
		$this->app['blog.posts_path'] = $this->app->share(function($app) {
			return app_path().'/posts/published/';
		});
	}

	protected function registerPostCompiler()
	{
		$this->app->bind('AdamWathan\Blog\PostCompilerInterface', 'AdamWathan\Blog\MarkdownPostCompiler');
	}

	protected function registerPostRepository()
	{
		$this->app->bind('AdamWathan\Blog\PostRepositoryInterface', function($app) {

			$repository = new FilePostRepository(
				$app['blog.posts_path'],
				$app->make('AdamWathan\Blog\PostCompilerInterface'),
				$app['files'],
				$app['cache'],
				$app['paginator']
				);

			return $repository;
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('blog');
	}
}